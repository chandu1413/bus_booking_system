<?php

namespace App\Services;

use App\Models\Project;
use App\Models\User;
use App\Services\ActivityLogService;
use Illuminate\Support\Facades\Cache;

class ProjectService
{
    public function __construct(private ActivityLogService $logService) {}

    public function create(array $data, User $owner): Project
    {
        $project = Project::create(array_merge($data, ['owner_id' => $owner->id]));
        // Auto-add owner as member
        $project->projectMembers()->create(['user_id' => $owner->id, 'role' => 'owner']);
        $this->logService->log($owner, $project, 'created', "Created project: {$project->name}");
        $this->clearCache();
        return $project;
    }

    public function update(Project $project, array $data, User $user): Project
    {
        $old = $project->status;
        $project->update($data);
        if ($old !== $project->status) {
            $this->logService->log($user, $project, 'status_changed',
                "Changed status from {$old} to {$project->status}");
        } else {
            $this->logService->log($user, $project, 'updated', "Updated project: {$project->name}");
        }
        $this->clearCache();
        return $project;
    }

    public function delete(Project $project, User $user): void
    {
        $this->logService->log($user, $project, 'deleted', "Deleted project: {$project->name}");
        $project->delete();
        $this->clearCache();
    }

    public function restore(Project $project, User $user): void
    {
        $project->restore();
        $this->logService->log($user, $project, 'restored', "Restored project: {$project->name}");
        $this->clearCache();
    }

    public function addMember(Project $project, User $member, string $role = 'member'): void
    {
        $project->projectMembers()->updateOrCreate(
            ['user_id' => $member->id],
            ['role' => $role]
        );
        $this->logService->log(auth()->user(), $project, 'member_added',
            "Added {$member->name} to project");
    }

    public function removeMember(Project $project, User $member): void
    {
        $project->projectMembers()->where('user_id', $member->id)->delete();
        $this->logService->log(auth()->user(), $project, 'member_removed',
            "Removed {$member->name} from project");
    }

    private function clearCache(): void
    {
        Cache::forget('admin_stats');
    }
}