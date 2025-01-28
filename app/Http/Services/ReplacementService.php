<?php

namespace App\Http\Services;

use App\Models\Part;
use App\Models\Submission;
use Illuminate\Database\Eloquent\Collection;

class ReplacementService
{
    /**
     * Replace part
     *
     * @var array
     */
    public function replacePart(array $replacementOption): void
    {
        $originalPart = Part::find($replacementOption['original']);
        $originalPart->replaced_by_submission = $replacementOption['reason'];
        $originalPart->redundant = 1;

        if ($replacementOption['reason'] == 'QTY changed') {
            $newPart = Part::find($replacementOption['new']);
            $newPart->quantity_in_stock = $originalPart->quantity_in_stock;
            $newPart->quantity_ordered = $originalPart->quantity_ordered;
            $newPart->save();
        }

        $originalPart->save();
    }

    /**
     * Get replacement options
     */
    public function getReplacementOptions(Collection $previousSubmissions, Submission $replacementSubmission): array
    {
        $replacementOptions = [];

        foreach ($previousSubmissions as $originalSubmission) {
            $differentOriginalParts = $originalSubmission->parts()->pluck('name', 'id')->toArray();
            $differentReplacementParts = $replacementSubmission->parts()->pluck('name', 'id')->toArray();

            [$identicalOriginalParts, $identicalReplacementParts] = $this->splitParts($differentOriginalParts, $differentReplacementParts);

            $this->addQtyChanges($identicalOriginalParts, $identicalReplacementParts, $replacementOptions);
            $this->addReplacements($differentOriginalParts, $differentReplacementParts, $replacementOptions);
        }

        return [$replacementOptions, $differentReplacementParts];
    }

    /**
     * Split parts
     */
    public function splitParts(array &$originalParts, array &$replacementParts): array
    {
        $identicalOriginalParts = [];
        $identicalReplacementParts = [];

        foreach ($originalParts as $originalPartId => $originalPartName) {
            if (in_array($originalPartName, $replacementParts)) {
                $identicalOriginalParts[$originalPartId] = $originalPartName;
                $identicalReplacementParts[array_search($originalPartName, $replacementParts)] = $originalPartName;
                unset($originalParts[$originalPartId]);
                unset($replacementParts[array_search($originalPartName, $replacementParts)]);
            }
        }

        return [$identicalOriginalParts, $identicalReplacementParts];
    }

    /**
     * Add quantity changes
     *
     * @return array
     */
    public function addQtyChanges(array $identicalOriginalParts, array $identicalReplacementParts, array &$replacementOptions)
    {
        foreach ($identicalOriginalParts as $originalPartId => $originalPartName) {
            $original = Part::find($originalPartId);
            $new = Part::find(array_search($originalPartName, $identicalReplacementParts));

            if ($original->quantity != $new->quantity) {
                $replacementOptions[] = [
                    'original' => $original->id,
                    'new' => $new->id,
                    'reason' => 'QTY changed',
                ];
            }
        }
    }

    /**
     * Add replacements
     */
    public function addReplacements(array $differentOriginalParts, array &$differentReplacementParts, array &$replacementOptions): void
    {
        foreach ($differentOriginalParts as $originalPartId => $originalPartName) {
            $replacementId = $this->findReplacement($originalPartName, $differentReplacementParts);

            if ($replacementId != -1) {
                $replacementOptions[] = [
                    'original' => $originalPartId,
                    'new' => $replacementId,
                    'reason' => "Replaced by {$replacementId} - {$differentReplacementParts[$replacementId]}",
                ];

                unset($differentReplacementParts[$replacementId]);
            }
        }
    }

    /**
     * Find replacement
     */
    public function findReplacement(string $originalPartName, array $replacementParts): int
    {
        // if original part name starts with "PPM"
        if (strtolower(substr($originalPartName, 0, 3)) == 'ppm') {
            $originalPartName = preg_replace('/\s+/', '', strtolower($originalPartName));
            $originalPartNameSyllables = explode('-', $originalPartName);
            $originalPartName = end($originalPartNameSyllables);

            foreach ($replacementParts as $replacementPartId => $replacementPartName) {
                $replacementPartName = preg_replace('/\s+/', '', strtolower($replacementPartName));
                $replacementPartNameSyllables = explode('-', $replacementPartName);
                $replacementPartName = end($replacementPartNameSyllables);

                if ($originalPartName == $replacementPartName) {
                    return $replacementPartId;
                }
            }
        } else {
            // don't do any fancy stuff, just look for exact match
            foreach ($replacementParts as $replacementPartId => $replacementPartName) {
                if ($originalPartName == $replacementPartName) {
                    return $replacementPartId;
                }
            }
        }

        return -1;
    }

    /**
     * Mark as redundant by deleting parts
     */
    public function markAsRedundant(Submission $newSubmission, array $replacementOptions, array $newParts): void
    {
        foreach ($newSubmission->parts as $part) {
            if (! in_array($part->id, array_column($replacementOptions, 'new')) && ! in_array($part->id, array_keys($newParts))) {
                $part->redundant = 1;
                $part->replaced_by_submission = 'Duplicate';
                $part->save();
                $part->delete();
            }
        }
    }
}
