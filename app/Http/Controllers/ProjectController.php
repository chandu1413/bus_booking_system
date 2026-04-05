<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Models\Project;
use App\Services\ProjectService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ProjectController extends Controller
{
    use AuthorizesRequests;

    public function __construct(private ProjectService $projectService) {}

    public function index()
    {
        $user = auth()->user();
        if (!$user) {
            return redirect()->route('login');
        }
        $projects = Project::forUser($user)
            ->with(['owner', 'tasks'])
            ->latest()
            ->paginate(12);
        $trashedProjects = [];
        if ($user->isSuperAdmin() || $user->isAdmin()) {
            $trashedProjects = Project::onlyTrashed()->with('owner')->latest('deleted_at')->get();
        }
        return view('projects.index', compact('projects', 'trashedProjects'));
    }

    public function create()
    {
        $this->authorize('create', Project::class);
        return view('projects.create');
    }

    public function store(StoreProjectRequest $request)
    {
        $project = $this->projectService->create($request->validated(), auth()->user());
        return redirect()->route('projects.show', $project)
            ->with('success', 'Project created successfully!');
    }

    public function show(Project $project)
    {
        $this->authorize('view', $project);
        $project->load([
            'owner',
            'members',
            'tasks' => fn($q) => $q->with(['assignee', 'subtasks'])->orderBy('order'),
        ]);
        $tasksByStatus = $project->tasks->groupBy('status');
        return view('projects.show', compact('project', 'tasksByStatus'));
    }

    public function edit(Project $project)
    {
        $this->authorize('update', $project);
        return view('projects.edit', compact('project'));
    }

    public function update(UpdateProjectRequest $request, Project $project)
    {
        $this->projectService->update($project, $request->validated(), auth()->user());
        return redirect()->route('projects.show', $project)
            ->with('success', 'Project updated successfully!');
    }

    public function destroy(Project $project)
    {
        $this->authorize('delete', $project);
        $this->projectService->delete($project, auth()->user());
        return redirect()->route('projects.index')
            ->with('success', 'Project moved to trash.');
    }

    public function restore($id)
    {
        $project = Project::onlyTrashed()->findOrFail($id);
        $this->authorize('restore', $project);
        $this->projectService->restore($project, auth()->user());
        return redirect()->route('projects.index')
            ->with('success', 'Project restored successfully!');
    }
}