<?php

namespace App\Services;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use App\Events\TaskStatusChanged;
use App\Events\TaskAssigned;

class TaskService
{
    public function __construct(private ActivityLogService $logService) {}

    public function create(Project $project, array $data, User $creator): Task
    {
        $data['project_id'] = $project->id;
        $data['created_by'] = $creator->id;
        $task = Task::create($data);
        $this->logService->log($creator, $task, 'created', "Created task: {$task->title}");
        if (!empty($task->assignee_id) && $task->assignee_id !== $creator->id) {
            event(new TaskAssigned($task, $task->assignee));
        }
        return $task;
    }

    public function update(Task $task, array $data, User $user): Task
    {
        $oldStatus   = $task->status;
        $oldAssignee = $task->assignee_id;
        $task->update($data);
        $task->refresh();
        if ($oldStatus !== $task->status) {
            $this->logService->log($user, $task, 'status_changed',
                "Status changed from {$oldStatus} to {$task->status}");
            event(new TaskStatusChanged($task, $oldStatus));
        } else {
            $this->logService->log($user, $task, 'updated', "Updated task: {$task->title}");
        }
        if ($oldAssignee !== $task->assignee_id && !empty($task->assignee_id) && $task->assignee_id !== $user->id) {
            event(new TaskAssigned($task, $task->assignee));
        }
        return $task;
    }

    public function updateStatus(Task $task, string $status, User $user): Task
    {
        $old = $task->status;
        $task->update(['status' => $status]);
        $this->logService->log($user, $task, 'status_changed',
            "Status changed from {$old} to {$status}");
        event(new TaskStatusChanged($task, $old));
        return $task;
    }

    public function delete(Task $task, User $user): void
    {
        $this->logService->log($user, $task, 'deleted', "Deleted task: {$task->title}");
        $task->delete();
    }
}