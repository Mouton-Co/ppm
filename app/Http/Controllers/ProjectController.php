<?php

namespace App\Http\Controllers;

use App\Http\Requests\Project\StoreRequest;
use App\Models\Project;
use App\Models\ProjectResponsible;
use App\Models\ProjectStatus;
use App\Models\User;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $projects = Project::with(['submission']);

        $responsibles = array_merge(
            ProjectResponsible::pluck('name')->toArray(),
            User::pluck('name')->toArray()
        );
        sort($responsibles);

        if (request()->has('machine_nr')) {
            $projects->where('machine_nr', 'like', '%' . request('machine_nr') . '%');
        }

        if ($request->has('country')) {
            $projects->where('country', 'like', '%' . request('country') . '%');
        }

        if ($request->has('currently_responsible')) {
            $projects->where('currently_responsible', 'like', '%' . request('currently_responsible') . '%');
        }

        if ($request->has('status')) {
            $projects->where('status', 'like', '%' . request('status') . '%');
        }

        $projects = $projects->paginate(15);

        if ($projects->currentPage() > 1 && $projects->lastPage() < $projects->currentPage()) {
            return redirect()->route('projects.index', [
                'machine_nr' => request('machine_nr'),
                'country' => request('country'),
                'currently_responsible' => request('currently_responsible'),
                'status' => request('status'),
                'page' => $projects->lastPage(),
            ]);
        }

        return view('project.index', [
            'projects' => $projects,
            'responsibles' => $responsibles,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $statuses = ProjectStatus::orderBy('name')->get();

        $responsibles = array_merge(
            ProjectResponsible::pluck('name')->toArray(),
            User::pluck('name')->toArray()
        );
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
        Project::create($request->validated());

        return redirect()->route('projects.index')->with('success', 'Project created successfully');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Project $project)
    {
        $statuses = ProjectStatus::orderBy('name')->get();
        $responsibles = array_merge(
            ProjectResponsible::pluck('name')->toArray(),
            User::pluck('name')->toArray()
        );
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
        $project->update($request->validated());

        return redirect()->back()->with('success', 'Project updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        $project->delete();

        return redirect()->back()->with('success', 'Project deleted successfully');
    }

    /**
     * Generate a new coc number for a machine
     * @param string $machineNr
     * @return \Illuminate\Http\JsonResponse
     */
    public function generateCoc($machineNr): \Illuminate\Http\JsonResponse
    {
        $projects = Project::where('coc', 'like', '%' . $machineNr . '_%')->get();

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
        $coc = $machineNr . '_' . str_pad($highest + 1, 3, '0', STR_PAD_LEFT);
        
        return response()->json(['coc' => $coc]);
    }

    /**
     * Update project status or resolved_at date
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateAjax(Request $request, $id): \Illuminate\Http\JsonResponse
    {
        $project = Project::findOrFail($id);

        if (empty($project)) {
            return response()->json([
                'message' => 'Project not found',
            ], 404);
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
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function unlink($id): \Illuminate\Http\RedirectResponse
    {
        $project = Project::findOrFail($id);

        if (empty($project)) {
            return redirect()->back()->with('error', 'Project not found');
        }

        $project->update([
            'submission_id' => null,
        ]);

        return redirect()->back()->with('success', 'Project deleted successfully');
    }
}
