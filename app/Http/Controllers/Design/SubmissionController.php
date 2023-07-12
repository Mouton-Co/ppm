<?php

namespace App\Http\Controllers\Design;

use App\Http\Controllers\Controller;
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
        ]);
    }

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

        return redirect()->route('dashboard.designer')->with([
            'success' => "Submission created - ".$submission->assembly_name,
        ]);
    }
}
