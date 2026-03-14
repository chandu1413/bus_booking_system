<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Project;
use App\Models\Task;
use App\Services\TaskService;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function __construct(private TaskService $taskService) {}

    public function index(Project $project)
    {
        $this->authorize('view', $project);
        $tasks = $project->tasks()
            ->with(['assignee', 'subtasks', 'comments', 'attachments'])
            ->orderBy('order')
            ->get();
        $members = $project->members()->get();
        return view('tasks.index', compact('project', 'tasks', 'members'));
    }

    public function create(Project $project)
    {
        $this->authorize('create', Task::class);
        $members = $project->members;
        return view('tasks.create', compact('project', 'members'));
    }

    public function store(StoreTaskRequest $request, Project $project)
    {
        $task = $this->taskService->create($project, $request->validated(), auth()->user());
        return redirect()->route('projects.tasks.show', [$project, $task])
            ->with('success', 'Task created successfully!');
    }

    public function show(Project $project, Task $task)
    {
        $this->authorize('view', $task);
        $task->load(['assignee', 'creator', 'subtasks', 'comments.user', 'attachments.user', 'activityLogs.user']);
        $members = $project->members;
        return view('tasks.show', compact('project', 'task', 'members'));
    }

    public function edit(Project $project, Task $task)
    {
        $this->authorize('update', $task);
        $members = $project->members;
        return view('tasks.edit', compact('project', 'task', 'members'));
    }

    public function update(UpdateTaskRequest $request, Project $project, Task $task)
    {
        $this->taskService->update($task, $request->validated(), auth()->user());
        return redirect()->route('projects.tasks.show', [$project, $task])
            ->with('success', 'Task updated successfully!');
    }

    public function destroy(Project $project, Task $task)
    {
        $this->authorize('delete', $task);
        $this->taskService->delete($task, auth()->user());
        return redirect()->route('projects.show', $project)
            ->with('success', 'Task deleted.');
    }

    public function updateStatus(Request $request, Project $project, Task $task)
    {
        $this->authorize('update', $task);
        $request->validate(['status' => 'required|in:todo,in_progress,in_review,done']);
        $this->taskService->updateStatus($task, $request->status, auth()->user());
        return back()->with('success', 'Task status updated.');
    }
}