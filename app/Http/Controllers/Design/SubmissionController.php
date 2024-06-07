<?php

namespace App\Http\Controllers\Design;

use App\Http\Controllers\Controller;
use App\Http\Controllers\PartsController;
use App\Http\Helpers\FileManagementHelper;
use App\Models\Project;
use App\Models\RecipientGroup;
use App\Models\Submission;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SubmissionController extends Controller
{
    public $request;

    /**
     * View for creating a new submission
     *
     * @return View
     */
    public function show()
    {
        $user = User::find(auth()->user()->id);
        $timestamp = Carbon::now()->format('YmdGis');

        // find a unique submission code
        // wait a second to get the next timestamp
        // failsafe for if user spams refresh button
        $submissionCode = strtoupper(substr($user->name, 0, 3)).$timestamp.$user->id;
        $exists = Submission::where('submission_code', $submissionCode)->first();

        while (! empty($exists)) {
            sleep(1);
            $submissionCode = strtoupper(substr($user->name, 0, 3)).$timestamp.$user->id;
            $exists = Submission::where('submission_code', $submissionCode)->first();
        }

        $submission = Submission::create([
            'submission_code' => $submissionCode,
            'user_id' => $user->id,
        ]);

        $projects = Project::whereNull('submission_id')->orderBy('coc')->get();

        return view('designer.new-submission')->with([
            'submission' => $submission,
            'submission_types' => config('dropdowns.submission_types'),
            'unit_numbers' => config('dropdowns.unit_numbers'),
            'projects' => $projects,
        ]);
    }

    /**
     * Store a new submission
     *
     * @return Redirect
     */
    public function store(Request $request)
    {
        $submission = Submission::where('submission_code', $request->get('submission_code'))->first();

        if (empty($submission)) {
            return redirect()->route('submissions.index')->with([
                'error' => 'Submission not found',
            ]);
        }

        $submission->user_id = auth()->user()->id;
        $submission->submission_code = $request->get('submission_code');
        $submission->assembly_name = $request->get('assembly_name');
        $submission->machine_number = $request->get('machine_number');
        $submission->submission_type = $request->get('submission_type');
        $submission->current_unit_number = $request->get('current_unit_number');
        $submission->notes = $request->get('notes');
        $submission->submitted = 1;
        $submission->project_id = $request->get('project_id') ?? null;
        $submission->save();

        // link the project to the submission
        if (! empty($request->get('project_id'))) {
            $project = Project::find($request->get('project_id'));
            $project->status = "Work in Progress";
            $project->submission_id = $submission->id;
            $project->save();
        }

        $fmh = new FileManagementHelper();
        $fmh->makeFilesPermanent($submission->submission_code);

        $partsController = new PartsController();
        $partsController->storeParts($submission);

        // send out email to recipients
        $group = RecipientGroup::where('field', "Item created")
            ->where('value', 'Submission')
            ->first();

        if (! empty($group)) {
            $group->mail('New Submission Available', 'emails.submission.created', $submission);
        }

        return redirect()->route('submissions.index')->with([
            'success' => 'Submission created - '.$submission->assembly_name,
        ]);
    }

    /**
     * View for showing all submissions
     *
     * @return View
     */
    public function index(Request $request)
    {
        // get all submissions
        $this->request = $request;
        $submissions = Submission::select(['submissions.*', 'users.name as user_name'])
            ->join('users', 'users.id', '=', 'submissions.user_id')
            ->where('submitted', 1);

        // order by
        if (! empty($request->get('order_by'))) {
            if ($request->get('order_by') == 'user->name') {
                $submissions = $submissions->orderBy('users.name', $request->get('order') ?? 'asc');
            } else {
                $submissions = $submissions->orderBy($request->get('order_by'), $request->get('order') ?? 'asc');
            }
        }

        // unit number
        if (! empty($request->get('current_unit_number')) && $request->get('current_unit_number') != '-') {
            $submissions = $submissions->where('current_unit_number', $request->get('current_unit_number'));
        }

        // submission type
        if (! empty($request->get('submission_type')) && $request->get('submission_type') != '-') {
            $submissions = $submissions->where('submission_type', $request->get('submission_type'));
        }

        // search
        if (! empty($request->get('search'))) {
            $submissions = $submissions
                ->where(function ($query) {
                    $query->where('assembly_name', 'like', '%'.$this->request->get('search').'%')
                        ->orWhere('submission_code', 'like', '%'.$this->request->get('search').'%')
                        ->orWhere('users.name', 'like', '%'.$this->request->get('search').'%');
                });
        }

        $submissions = $submissions->paginate(15);

        if ($submissions->currentPage() > 1 && $submissions->lastPage() < $submissions->currentPage()) {
            return redirect()->route('submissions.index', [
                'order_by' => $request->get('order_by'),
                'order' => $request->get('order'),
                'current_unit_number' => $request->get('current_unit_number'),
                'submission_type' => $request->get('submission_type'),
                'search' => $request->get('search'),
                'page' => $submissions->lastPage(),
            ]);
        }

        return view('submissions.index')->with([
            'current' => 'view-submissions',
            'submissions' => $submissions,
            'fields' => config('models.submissions.columns'),
        ]);
    }

    /**
     * View for showing specific submission
     *
     * @return View
     */
    public function view($id)
    {
        $submission = Submission::find($id);

        if (empty($submission)) {
            return redirect()->route('submissions.index')->with([
                'error' => "Submission $id not found",
            ]);
        }

        return view('submissions.view')->with([
            'submission' => $submission,
        ]);
    }

    /**
     * Delete a submission
     *
     * @return Redirect
     */
    public function destroy($id)
    {
        $submission = Submission::find($id);

        if (empty($submission)) {
            return redirect()->route('submissions.index')->with([
                'error' => "Submission $id not found",
            ]);
        }

        // delete files for submission
        Storage::disk('local')->deleteDirectory('files/'.$submission->submission_code);

        // delete parts and files
        foreach ($submission->parts as $part) {
            $part->files()->delete();
            $part->delete();
        }

        // remove links from any projects
        $projects = Project::where('submission_id', $submission->id)->get();
        foreach ($projects as $project) {
            $project->submission_id = null;
            $project->save();
        }

        $code = $submission->submission_code;
        $submission->delete();

        return redirect()->route('submissions.index')->with([
            'success' => "Submission $code deleted",
        ]);
    }
}
