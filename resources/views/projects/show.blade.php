@extends('layouts.app')
@section('title', $project->name)
@section('page-title', $project->name)
@section('breadcrumb')
    {!! '<a href="'.route('projects.index').'" class="text-indigo-600 hover:underline">Projects</a> / '.e($project->name) !!}
@endsection
@section('content')
<div class="flex items-start justify-between mb-6">
    <div class="flex items-center space-x-3">
        <div class="w-4 h-4 rounded-full" style="background: {{ $project->color }}"></div>
        <span class="px-3 py-1 rounded-full text-sm font-medium
            @if($project->status === 'Active') bg-green-100 text-green-700
            @elseif($project->status === 'OnHold') bg-yellow-100 text-yellow-700
            @else bg-blue-100 text-blue-700 @endif">{{ $project->status }}</span>
        <span class="text-sm text-gray-500">{{ $project->completion_percentage }}% complete</span>
    </div>
    <div class="flex space-x-2">
        <a href="{{ route('projects.members.index', $project) }}" class="px-3 py-2 text-sm border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
            <i class="fas fa-users mr-1"></i>Members
        </a>
        <a href="{{ route('projects.tasks.create', $project) }}" class="px-3 py-2 text-sm bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
            <i class="fas fa-plus mr-1"></i>Add Task
        </a>
        @can('update', $project)
        <a href="{{ route('projects.edit', $project) }}" class="px-3 py-2 text-sm border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
            <i class="fas fa-pencil mr-1"></i>Edit
        </a>
        @endcan
    </div>
</div>

@if($project->description)
<div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 mb-6">
    <p class="text-gray-600">{{ $project->description }}</p>
    @if($project->start_date || $project->end_date)
    <div class="flex space-x-6 mt-3 text-sm text-gray-500">
        @if($project->start_date)<span><i class="fas fa-play text-green-500 mr-1"></i>{{ $project->start_date->format('M d, Y') }}</span>@endif
        @if($project->end_date)<span><i class="fas fa-flag text-red-500 mr-1"></i>{{ $project->end_date->format('M d, Y') }}</span>@endif
    </div>
    @endif
</div>
@endif

<!-- Kanban Board -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-4">
    @foreach(['todo' => 'To Do', 'in_progress' => 'In Progress', 'in_review' => 'In Review', 'done' => 'Done'] as $status => $label)
    <div class="bg-gray-100 rounded-xl p-4">
        <div class="flex items-center justify-between mb-3">
            <h3 class="font-semibold text-gray-700 text-sm">{{ $label }}</h3>
            <span class="bg-white text-gray-600 text-xs px-2 py-0.5 rounded-full">{{ ($tasksByStatus[$status] ?? collect())->count() }}</span>
        </div>
        <div class="space-y-2">
            @forelse($tasksByStatus[$status] ?? [] as $task)
            <a href="{{ route('projects.tasks.show', [$project, $task]) }}" class="block bg-white rounded-lg p-3 shadow-sm hover:shadow-md transition-shadow">
                <div class="flex items-start justify-between">
                    <p class="text-sm font-medium text-gray-800 leading-tight">{{ $task->title }}</p>
                    <span class="ml-2 px-1.5 py-0.5 text-xs rounded font-medium flex-shrink-0
                        @if($task->priority === 'urgent') bg-red-100 text-red-700
                        @elseif($task->priority === 'high') bg-orange-100 text-orange-700
                        @elseif($task->priority === 'medium') bg-blue-100 text-blue-700
                        @else bg-gray-100 text-gray-600 @endif">{{ $task->priority }}</span>
                </div>
                <div class="flex items-center justify-between mt-2">
                    <div class="flex items-center space-x-2 text-xs text-gray-400">
                        @if($task->subtasks->count())
                        <span><i class="fas fa-list-check mr-0.5"></i>{{ $task->subtasks->where('is_completed', true)->count() }}/{{ $task->subtasks->count() }}</span>
                        @endif
                        @if($task->due_date)
                        <span class="{{ $task->is_overdue ? 'text-red-500' : '' }}"><i class="fas fa-calendar mr-0.5"></i>{{ $task->due_date->format('M d') }}</span>
                        @endif
                    </div>
                    @if($task->assignee)
                    <img src="{{ $task->assignee->avatar_url }}" class="w-6 h-6 rounded-full" title="{{ $task->assignee->name }}">
                    @endif
                </div>
            </a>
            @empty
            <p class="text-xs text-gray-400 text-center py-4">No tasks</p>
            @endforelse
        </div>
    </div>
    @endforeach
</div>
@endsection
