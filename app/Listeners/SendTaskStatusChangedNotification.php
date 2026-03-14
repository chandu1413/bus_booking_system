<?php

namespace App\Listeners;

use App\Events\TaskStatusChanged;
use App\Notifications\TaskStatusChangedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendTaskStatusChangedNotification implements ShouldQueue
{
    public function handle(TaskStatusChanged $event): void
    {
        $task = $event->task->load('project.owner', 'assignee');
        $notifiables = collect();
        if ($task->project->owner) $notifiables->push($task->project->owner);
        if ($task->assignee && $task->assignee->id !== $task->project->owner_id) {
            $notifiables->push($task->assignee);
        }
        foreach ($notifiables->unique('id') as $user) {
            $user->notify(new TaskStatusChangedNotification($task, $event->oldStatus));
        }
    }
}