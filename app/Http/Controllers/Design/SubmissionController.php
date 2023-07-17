<?php

namespace App\Http\Controllers\Design;

use App\Http\Controllers\Controller;
use App\Http\Controllers\PartsController;
use App\Http\Helpers\FileManagementHelper;
use App\Models\Submission;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SubmissionController extends Controller
{
    /**
     * View for creating a new submission
     *
     * @return View
     */
    public function show()
    {
        $user      = User  ::find(auth()->user()->id);
        $timestamp = Carbon::now()->format('YmdGis');

        // find a unique submission code
        // wait a second to get the next timestamp
        // failsafe for if user spams refresh button
        $submissionCode = strtoupper(substr($user->name, 0, 3)).$timestamp.$user->id;
        $exists         = Submission::where('submission_code', $submissionCode)->first();

        while (!empty($exists)) {
            sleep(1);
            $submissionCode = strtoupper(substr($user->name, 0, 3)).$timestamp.$user->id;
            $exists         = Submission::where('submission_code', $submissionCode)->first();
        }

        $submission = Submission::create([
            'submission_code' => $submissionCode,
            'user_id'         => $user->id,
        ]);
        
        return view('designer.new-submission')->with([
            'submission'       => $submission,
            'submission_types' => config('dropdowns.submission_types'),
            'unit_numbers'     => config('dropdowns.unit_numbers'),
            'current'          => 'new-submission',
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
            return redirect()->route('dashboard.designer')->with([
                'error' => "Submission not found",
            ]);
        }

        $submission->user_id             = auth()->user()->id;
        $submission->submission_code     = $request->get('submission_code');
        $submission->assembly_name       = $request->get('assembly_name');
        $submission->machine_number      = $request->get('machine_number');
        $submission->submission_type     = $request->get('submission_type');
        $submission->current_unit_number = $request->get('current_unit_number');
        $submission->notes               = $request->get('notes');
        $submission->submitted           = 1;
        $submission->save();
        
        $fmh = new FileManagementHelper();
        $fmh->makeFilesPermanent($submission->submission_code);

        $partsController = new PartsController();
        $partsController->storeParts($submission);


        return redirect()->route('submissions.index')->with([
            'success' => "Submission created - ".$submission->assembly_name,
        ]);
    }

    /**
     * View for showing all submissions
     *
     * @return View
     */
    public function index()
    {
        $userRoles   = auth()->user()->roles->pluck('role')->all();
        $submissions = Submission::where('submitted', 1);

        if (
            !(in_array('admin', $userRoles)
            || in_array('procurement', $userRoles))
        ) {
            // can only view own submissions
            $submissions = $submissions->where('user_id', auth()->user()->id);
        }

        $submissions = $submissions->orderBy('created_at', 'desc')->get();
        
        return view('submissions.index')->with([
            'current'     => 'view-submissions',
            'submissions' => $submissions,
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
}
