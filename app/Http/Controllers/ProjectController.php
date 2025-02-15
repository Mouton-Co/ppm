<?php

namespace App\Http\Controllers;

use App\Http\Requests\Project\StoreRequest;
use App\Mail\ProjectUpdate;
use App\Models\Project;
use App\Models\ProjectResponsible;
use App\Models\ProjectStatus;
use App\Models\RecipientGroup;
use App\Models\Submission;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ProjectController extends Controller
{
    /**
     * ProjectController constructor.
     */
    public function __construct()
    {
        $this->model = Project::class;
        $this->route = 'projects';
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->user()->cannot('read', Project::class) && ! $request->user()->role->customer) {
            abort(403);
        }

        $this->checkTableConfigurations('projects', Project::class);

        if (auth()->user()->role->customer) {
            $projects = $this->filter(Project::class, Project::query(), $request)
                ->whereIn('machine_nr', json_decode(auth()->user()->role->permissions))
                ->paginate(15);
        } elseif ($request->has('status') && $request->status === 'All except closed') {
            $projects = $this->filter(Project::class, Project::query(), $request)
                ->where('status', '!=', 'Closed')
                ->paginate(15);
        } else {
            $projects = $this->filter(Project::class, Project::query(), $request)->paginate(15);
        }

        if ($projects->currentPage() > 1 && $projects->lastPage() < $projects->currentPage()) {
            return redirect()->route('projects.index', array_merge(['page' => $projects->lastPage()], $request->except(['page'])));
        }

        // get all available submissions for link modal
        $availableSubmissions = Submission::whereNull('project_id')->where('submitted', 1)->get();

        return view('generic.index')->with([
            'heading' => 'Projects',
            'table' => 'projects',
            'route' => 'projects',
            'indexRoute' => 'projects.index',
            'data' => $projects,
            'model' => Project::class,
            'slot' => 'components.table.project.link-submission-modal',
            'availableSubmissions' => $availableSubmissions,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        if ($request->user()->cannot('create', Project::class)) {
            abort(403);
        }

        $statuses = ProjectStatus::orderBy('name')->get();

        $responsibles = array_merge(ProjectResponsible::pluck('name')->toArray(), User::pluck('name')->toArray());
        sort($responsibles);

        return view('project.create', [
            'statuses' => $statuses,
            'responsibles' => $responsibles,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        if ($request->user()->cannot('create', Project::class)) {
            abort(403);
        }

        Project::create(
            array_merge($request->validated(), [
                'user_id' => auth()->user()->id,
            ]),
        );

        return redirect()->route('projects.index')->with('success', 'Project created successfully');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, Project $project)
    {
        if ($request->user()->cannot('update', Project::class)) {
            abort(403);
        }

        $statuses = ProjectStatus::orderBy('name')->get();
        $responsibles = array_merge(ProjectResponsible::pluck('name')->toArray(), User::pluck('name')->toArray());
        sort($responsibles);

        return view('project.edit', [
            'project' => $project,
            'statuses' => $statuses,
            'responsibles' => $responsibles,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreRequest $request, Project $project)
    {
        if ($request->user()->cannot('update', Project::class)) {
            abort(403);
        }

        $project->update($request->validated());

        return redirect()->back()->with('success', 'Project updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Project $project)
    {
        if ($request->user()->cannot('delete', Project::class)) {
            abort(403);
        }

        $project->delete();

        return redirect()->back()->with('success', 'Project deleted successfully');
    }

    /**
     * Generate a new coc number for a machine
     *
     * @param  string  $machineNr
     */
    public function generateCoc($machineNr): \Illuminate\Http\JsonResponse
    {
        $projects = Project::where('coc', 'like', '%'.$machineNr.'_%')->get();

        // get the highest coc number
        $highest = 0;
        foreach ($projects as $project) {
            $number = explode('_', $project->coc);
            $number = end($number);
            if ((int) $number > $highest) {
                $highest = $number;
            }
        }

        // generate new coc number in format machineNr_###
        $coc = $machineNr.'_'.str_pad($highest + 1, 3, '0', STR_PAD_LEFT);

        return response()->json(['coc' => $coc]);
    }

    /**
     * Update project status or resolved_at date
     */
    public function updateAjax(Request $request, $id): \Illuminate\Http\JsonResponse
    {
        $project = Project::findOrFail($id);

        if (empty($project)) {
            return response()->json(
                [
                    'message' => 'Project not found',
                ],
                404,
            );
        }

        // if currently_responsible is updated from 'Design' to anything update status to `Design`
        if ($request->field === 'currently_responsible' && $project->currently_responsible === 'Design') {
            $project->update([
                'status' => 'Design',
            ]);
        }

        // if PO is added update status to `Order placed`
        if ($request->field === 'related_pos' && ! empty($request->value)) {
            $project->update([
                'status' => 'Order placed',
            ]);
        }

        // if waybill is added change status to `Shipped`
        if ($request->field === 'waybill_nr' && ! empty($request->value)) {
            $project->update([
                'status' => 'Shipped',
            ]);
        }

        // if status is moved the closed set resolved at
        if ($request->field === 'status' && $request->value === 'Closed') {
            $project->update([
                'resolved_at' => now(),
            ]);
        }

        // if status is moved from closed set resolved at to null\
        if ($request->field === 'status' && $project->status === 'Closed') {
            $project->update([
                'resolved_at' => null,
            ]);
        }

        $project->update([
            $request->field => $request->value,
        ]);

        return response()->json([
            'message' => 'Project updated successfully',
            'update' => [
                'id' => $project->id,
                'status' => $project->status,
                'resolved_at' => $project->resolved_at_formatted,
            ],
        ]);
    }

    /**
     * Unlink a submission from a project
     */
    public function unlink($id): \Illuminate\Http\RedirectResponse
    {
        $project = Project::findOrFail($id);
        $project->submission->update([
            'project_id' => null,
        ]);

        if (empty($project)) {
            return redirect()->back()->with('error', 'Project not found');
        }

        $project->update([
            'submission_id' => null,
        ]);

        return redirect()->back()->with('success', 'Submission unlinked');
    }

    /**
     * Link a submission to a project
     */
    public function link($id, Request $request): \Illuminate\Http\JsonResponse
    {
        $project = Project::findOrFail($id);

        if (empty($project)) {
            return response()->json([
                'error' => 'Project not found',
            ]);
        }

        $project->update([
            'submission_id' => $request->submission_id,
        ]);

        $submission = Submission::findOrFail($request->submission_id);

        if (empty($submission)) {
            return response()->json([
                'error' => 'Submission not found',
            ]);
        }

        $submission->update([
            'project_id' => $project->id,
        ]);

        return response()->json([
            'success' => 'Submission linked to project',
        ]);
    }

    /**
     * Send an update to the currently responsible department or individual
     */
    public function sendUpdate($id): void
    {
        $project = Project::findOrFail($id);

        if (! empty($project)) {
            $group = RecipientGroup::where('field', 'Currently responsible')
                ->where('value', $project->currently_responsible)
                ->first();

            if (! empty($group)) {
                $group->mail('New CoC Ticket', 'emails.project.assigned-department', $project);
            }

            $individual = User::where('name', $project->currently_responsible)->first();

            if (! empty($individual)) {
                Mail::to($individual->email)->send(new ProjectUpdate('New CoC Ticket', 'emails.project.assigned-individual', $project));
            }
        }
    }

    /**
     * Update checkbox with ajax
     */
    public function updateCheckbox(Request $request)
    {
        if (! $request->user()->role->hasPermission('update_projects')) {
            abort(403);
        }

        $project = Project::find($request->id);

        if (empty($project)) {
            return response()->json([
                'success' => false,
                'message' => 'Project not found',
            ], 404);
        }

        $project->{$request->get('field')} = $request->get('value');
        $project->save();

        return response()->json([
            'success' => true,
            'message' => 'Project updated successfully',
        ]);
    }
}
