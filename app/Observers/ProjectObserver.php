<?php

namespace App\Observers;

use App\Mail\ProjectUpdate;
use App\Models\Project;
use App\Models\RecipientGroup;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class ProjectObserver
{
    /**
     * Handle the Project "created" event.
     */
    public function created(Project $project): void
    {
        $group = RecipientGroup::where('field', "Item created")
            ->where('value', 'CoC')
            ->first();

        if (! empty($group)) {
            $group->mail("{$project->country} CoC {$project->coc}", 'emails.project.created', $project);
        }
    }

    /**
     * Handle the Project "updated" event.
     */
    public function updated(Project $project): void
    {
        if ($project->isDirty('status')) {
            $group = RecipientGroup::where('field', "Status")
                ->where('value', $project->status)
                ->first();

            if (! empty($group)) {
                $group->mail('Status Change on CoC Ticket', 'emails.project.status', $project);
            }

            $group = RecipientGroup::where('field', "Status updated for")
                ->where('value', 'LIKE', "%{$project->machine_nr}%")
                ->first();

            if (! empty($group)) {
                $group->mail("Status Change for Machine # {$project->machine_nr}", 'emails.project.status', $project);
            }
        }
    }

    /**
     * Handle the Project "deleted" event.
     */
    public function deleted(Project $project): void
    {
        //
    }

    /**
     * Handle the Project "restored" event.
     */
    public function restored(Project $project): void
    {
        //
    }

    /**
     * Handle the Project "force deleted" event.
     */
    public function forceDeleted(Project $project): void
    {
        //
    }
}
