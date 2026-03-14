<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;

class TaskPolicy
{
    public function view(User $user, Task $task): bool
    {
        if ($user->isSuperAdmin() || $user->isAdmin()) return true;
        $project = $task->project;
        return $project->owner_id === $user->id
            || $project->members()->where('users.id', $user->id)->exists();
    }

    public function create(User $user): bool
    {
        return $user->can('manage_tasks') || $user->isSuperAdmin() || $user->isAdmin();
    }

    public function update(User $user, Task $task): bool
    {
        if ($user->isSuperAdmin() || $user->isAdmin()) return true;
        return $task->project->owner_id === $user->id
            || $task->assignee_id === $user->id
            || $task->created_by === $user->id;
    }

    public function delete(User $user, Task $task): bool
    {
        if ($user->isSuperAdmin() || $user->isAdmin()) return true;
        return $task->project->owner_id === $user->id || $task->created_by === $user->id;
    }
}