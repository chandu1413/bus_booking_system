<?php

namespace App\Listeners;

use App\Events\ProjectCreated;
use App\Models\User;
use App\Notifications\ProjectCreatedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendProjectCreatedNotification implements ShouldQueue
{
    public function handle(ProjectCreated $event): void
    {
        // Send to project owner
        $event->project->owner->notify(new ProjectCreatedNotification($event->project));

        // Send to all admins and super admins
        $admins = User::where(function ($query) {
            $query->where('role', 'admin')
                  ->orWhere('role', 'superadmin');
        })->get();

        foreach ($admins as $admin) {
            // Don't send duplicate if admin is the owner
            if ($admin->id !== $event->project->owner_id) {
                $admin->notify(new ProjectCreatedNotification($event->project));
            }
        }
    }
}
