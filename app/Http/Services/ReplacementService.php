<?php

namespace App\Http\Services;

use App\Models\Part;
use App\Models\Submission;

class ReplacementService
{
    /**
     * Get the previous submission
     *
     * @param $submission
     * @return Submission|null
     */
    public function getPreviousSubmission(Submission $submission): ?Submission
    {
        return Submission::where('machine_number', $submission->machine_number)
            ->where('current_unit_number', $submission->current_unit_number)
            ->where('created_at', '<', $submission->created_at)
            ->where('submission_type', 2)
            ->where('submitted', 1)
            ->orderBy('created_at', 'desc')
            ->first();
    }

    /**
     * Replace parts
     *
     * @param array $partIds
     * @param string $submissionCode
     */
    public function replaceParts(array $partIds = [], string $submissionCode = ''): void
    {
        Part::whereIn('id', $partIds)->update([
            'replaced_by_submission' => $submissionCode,
        ]);
    }
}
