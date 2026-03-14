<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use App\Services\ProjectService;
use Illuminate\Http\Request;

class ProjectMemberController extends Controller
{
    public function __construct(private ProjectService $projectService) {}

    public function index(Project $project)
    {
        $this->authorize('view', $project);
        $project->load('members', 'owner');
        $availableUsers = User::whereNotIn('id', $project->members->pluck('id'))
            ->where('id', '!=', $project->owner_id)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
        return view('projects.members', compact('project', 'availableUsers'));
    }

    public function store(Request $request, Project $project)
    {
        $this->authorize('manageMembers', $project);
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'role'    => 'required|in:member,lead,reviewer',
        ]);
        $user = User::findOrFail($request->user_id);
        $this->projectService->addMember($project, $user, $request->role);
        return back()->with('success', "{$user->name} added to project!");
    }

    public function destroy(Project $project, User $user)
    {
        $this->authorize('manageMembers', $project);
        if ($project->owner_id === $user->id) {
            return back()->with('error', 'Cannot remove the project owner.');
        }
        $this->projectService->removeMember($project, $user);
        return back()->with('success', "{$user->name} removed from project.");
    }
}