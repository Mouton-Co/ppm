<?php

namespace App\Http\Controllers\Design;

use App\Http\Controllers\Controller;
use App\Http\Controllers\PartsController;
use App\Http\Helpers\FileManagementHelper;
use App\Http\Services\ReplacementService;
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
     * @var ReplacementService
     */
    public ReplacementService $replacementService;

    /**
     * SubmissionController constructor.
     */
    public function __construct()
    {
        $this->model = Submission::class;
        $this->route = 'submissions';
        $this->replacementService = new ReplacementService();
    }

    /**
     * View for creating a new submission
     *
     * @return View
     */
    public function show(Request $request)
    {
        if ($request->user()->cannot('read', Submission::class) || $request->user()->cannot('create', Submission::class)) {
            abort(403);
        }

        $user = User::find(auth()->user()->id);
        $timestamp = Carbon::now()->format('YmdGis');

        // find a unique submission code
        // wait a second to get the next timestamp
        // failsafe for if user spams refresh button
        $submissionCode = strtoupper(substr($user->name, 0, 3)) . $timestamp . $user->id;
        $exists = Submission::where('submission_code', $submissionCode)->first();

        while (!empty($exists)) {
            sleep(1);
            $submissionCode = strtoupper(substr($user->name, 0, 3)) . $timestamp . $user->id;
            $exists = Submission::where('submission_code', $submissionCode)->first();
        }

        $submission = Submission::create([
            'submission_code' => $submissionCode,
            'user_id' => $user->id,
        ]);

        $projects = Project::whereNull('submission_id')->orderBy('coc')->get();

        return view('designer.new-submission')->with([
            'submission' => $submission,
            'submission_types' => Submission::$structure['submission_type']['filterable_options'],
            'unit_numbers' => Submission::$structure['current_unit_number']['filterable_options'],
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
        if ($request->user()->cannot('create', Submission::class)) {
            abort(403);
        }

        $submission = Submission::where('submission_code', $request->get('submission_code'))->first();

        if (empty($submission)) {
            return redirect()
                ->route('submissions.index')
                ->with([
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
        if (!empty($request->get('project_id'))) {
            $project = Project::find($request->get('project_id'));
            $project->status = 'Work in Progress';
            $project->submission_id = $submission->id;
            $project->save();
        }

        $fmh = new FileManagementHelper();
        $fmh->makeFilesPermanent($submission->submission_code);

        $partsController = new PartsController();
        $partsController->storeParts($submission, $request->get('quantity') ?? 1);

        // send out email to recipients
        $group = RecipientGroup::where('field', 'Item created')->where('value', 'Submission')->first();

        if (!empty($group)) {
            $group->mail('New Submission Available', 'emails.submission.created', $submission);
        }

        $replacement = null;
        if ($submission->submission_type == 3) {
            $replacement = $this->replacementService->getPreviousSubmission($submission);
        }

        return redirect()
            ->route('submissions.index')
            ->with([
                'success' => 'Submission created - ' . $submission->assembly_name,
                'submission' => $submission,
                'replacement' => $replacement,
            ]);
    }

    /**
     * View for showing all submissions
     *
     * @return View
     */
    public function index(Request $request)
    {
        if ($request->user()->cannot('read', Submission::class)) {
            abort(403);
        }

        $this->checkTableConfigurations('submissions', Submission::class);
        $submissions = $this->filter(Submission::class, Submission::query(), $request)
            ->where('submitted', 1)
            ->paginate(15);

        if ($submissions->currentPage() > 1 && $submissions->lastPage() < $submissions->currentPage()) {
            return redirect()->route('submissions.index', array_merge(['page' => $submissions->lastPage()], $request->except(['page'])));
        }

        if (!empty($request->session()->get('submission')) && !empty($request->session()->get('replacement'))) {
            $replacementOptions = $this->replacementService->getReplacementOptions($request->session()->get('replacement'), $request->session()->get('submission'))[0] ?? [];
        }

        return view('generic.index')->with([
            'heading' => 'Submission',
            'table' => 'submissions',
            'route' => 'submissions',
            'indexRoute' => 'submissions.index',
            'data' => $submissions,
            'model' => Submission::class,
            'submission' => $request->session()->get('submission') ?? null,
            'replacement' => $request->session()->get('replacement') ?? null,
            'replacementOptions' => $replacementOptions ?? [],
        ]);
    }

    /**
     * View for showing specific submission
     *
     * @return View
     */
    public function view(Request $request, $id)
    {
        if ($request->user()->cannot('read', Submission::class)) {
            abort(403);
        }

        $submission = Submission::find($id);

        if (empty($submission)) {
            return redirect()
                ->route('submissions.index')
                ->with([
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
    public function destroy(Request $request, $id)
    {
        if ($request->user()->cannot('delete', Submission::class)) {
            abort(403);
        }

        $submission = Submission::find($id);

        if (empty($submission)) {
            return redirect()
                ->route('submissions.index')
                ->with([
                    'error' => "Submission $id not found",
                ]);
        }

        // delete parts
        foreach ($submission->parts as $part) {
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

        return redirect()
            ->route('submissions.index')
            ->with([
                'success' => "Submission $code deleted",
            ]);
    }

    /**
     * Restore the specified resource from storage.
     */
    public function restore(Request $request, string $id)
    {
        if ($request->user()->cannot('restore', Submission::class)) {
            abort(403);
        }

        $datum = $this->model::withTrashed()->find($id);

        if (empty($datum)) {
            return redirect()->route("{$this->route}.index");
        }

        // restore parts
        foreach ($datum->parts()->withTrashed()->get() as $part) {
            $part->restore();
        }

        $datum->restore();

        return redirect()
            ->route("{$this->route}.index", ['archived' => 'true'])
            ->with([
                'success' => 'Item has been restored',
            ]);
    }

    /**
     * Trash the specified resource from storage.
     */
    public function trash(Request $request, string $id)
    {
        if ($request->user()->cannot('forceDelete', Submission::class)) {
            abort(403);
        }

        $datum = $this->model::withTrashed()->find($id);

        if (empty($datum)) {
            return redirect()->route("{$this->route}.index");
        }

        // delete files for submission
        Storage::disk('s3')->deleteDirectory(env('APP_ENV') . '/files/' . $datum->submission_code);

        // delete parts and files
        foreach ($datum->parts()->withTrashed()->get() as $part) {
            $part->files()->delete();
            $part->forceDelete();
        }

        $datum->forceDelete();

        return redirect()
            ->route("{$this->route}.index", ['archived' => 'true'])
            ->with([
                'success' => 'Item has been permanently deleted',
            ]);
    }

    /**
     * Replace parts
     *
     * @return Redirect
     */
    public function replace(Request $request)
    {
        if ($request->user()->cannot('create', Submission::class)) {
            abort(403);
        }

        [$replacementOptions, $newParts] = $this->replacementService->getReplacementOptions(Submission::find($request->get('original_id')), Submission::find($request->get('new_id')));

        foreach ($request->except(['_token', 'original_id', 'new_id']) as $partId => $value) {
            if ($value == 'on') {
                $this->replacementService->replacePart($replacementOptions[$partId]);
            }
        }

        $this->replacementService->markAsRedundant(Submission::find($request->get('new_id')), $replacementOptions, $newParts);

        return redirect()
            ->back()
            ->with([
                'success' => 'Selected parts have been replaced',
            ]);
    }
}
