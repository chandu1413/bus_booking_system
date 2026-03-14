<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;

class ProjectPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Project $project): bool
    {
        if ($user->isSuperAdmin() || $user->isAdmin()) return true;
        return $project->owner_id === $user->id
            || $project->members()->where('users.id', $user->id)->exists();
    }

    public function create(User $user): bool
    {
        return $user->can('manage_projects') || $user->isSuperAdmin() || $user->isAdmin();
    }

    public function update(User $user, Project $project): bool
    {
        if ($user->isSuperAdmin()) return true;
        return $project->owner_id === $user->id || $user->isAdmin();
    }

    public function delete(User $user, Project $project): bool
    {
        if ($user->isSuperAdmin()) return true;
        return $project->owner_id === $user->id || $user->isAdmin();
    }

    public function restore(User $user, Project $project): bool
    {
        return $user->isSuperAdmin() || $user->isAdmin();
    }

    public function manageMembers(User $user, Project $project): bool
    {
        if ($user->isSuperAdmin()) return true;
        return $project->owner_id === $user->id || $user->isAdmin();
    }
}