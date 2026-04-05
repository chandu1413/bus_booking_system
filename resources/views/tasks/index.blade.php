@extends('layouts.app')
@section('title', 'Tasks')
@section('page-title', $project->name . ' — Tasks')
@section('breadcrumb')
    {!! '<a href="'.route('projects.index').'" class="text-indigo-600 hover:underline">Projects</a> / <a href="'.route('projects.show', $project).'" class="text-indigo-600 hover:underline">'.e($project->name).'</a> / Tasks' !!}
@endsection
@section('content')
<div class="flex items-center justify-between mb-5">
    <div class="text-sm text-gray-500">{{ $tasks->count() }} tasks total</div>
    <a href="{{ route('projects.tasks.create', $project) }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm">
        <i class="fas fa-plus mr-2"></i>New Task
    </a>
</div>
<div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
    <table class="min-w-full">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase">Task</th>
                <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase">Priority</th>
                <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase">Assignee</th>
                <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase">Due Date</th>
                <th class="px-5 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
        @forelse($tasks as $task)
        <tr class="hover:bg-gray-50">
            <td class="px-5 py-3">
                <div>
                    <a href="{{ route('projects.tasks.show', [$project, $task]) }}" class="text-sm font-medium text-gray-800 hover:text-indigo-600">{{ $task->title }}</a>
                    @if($task->subtasks->count())<p class="text-xs text-gray-400"><i class="fas fa-list-check mr-1"></i>{{ $task->subtasks->where('is_completed', true)->count() }}/{{ $task->subtasks->count() }}</p>@endif
                </div>
            </td>
            <td class="px-5 py-3">
                <span class="px-2 py-1 text-xs rounded-full font-medium
                    @if($task->status === 'done') bg-green-100 text-green-700
                    @elseif($task->status === 'in_progress') bg-blue-100 text-blue-700
                    @elseif($task->status === 'in_review') bg-purple-100 text-purple-700
                    @else bg-gray-100 text-gray-700 @endif">{{ $task->status_label }}</span>
            </td>
            <td class="px-5 py-3">
                <span class="px-2 py-1 text-xs rounded-full font-medium
                    @if($task->priority === 'urgent') bg-red-100 text-red-700
                    @elseif($task->priority === 'high') bg-orange-100 text-orange-700
                    @elseif($task->priority === 'medium') bg-blue-100 text-blue-700
                    @else bg-gray-100 text-gray-600 @endif">{{ ucfirst($task->priority) }}</span>
            </td>
            <td class="px-5 py-3">
                @if($task->assignee)
                <div class="flex items-center space-x-2">
                    <img src="{{ $task->assignee->avatar_url }}" class="w-6 h-6 rounded-full">
                    <span class="text-sm text-gray-600">{{ $task->assignee->name }}</span>
                </div>
                @else<span class="text-sm text-gray-400">—</span>@endif
            </td>
            <td class="px-5 py-3">
                @if($task->due_date)
                <span class="text-sm {{ $task->is_overdue ? 'text-red-600 font-medium' : 'text-gray-600' }}">
                    {{ $task->is_overdue ? '⚠️ ' : '' }}{{ $task->due_date->format('M d, Y') }}
                </span>
                @else<span class="text-gray-400">—</span>@endif
            </td>
            <td class="px-5 py-3 text-right">
                <a href="{{ route('projects.tasks.show', [$project, $task]) }}" class="text-indigo-600 hover:text-indigo-800 text-sm mr-3"><i class="fas fa-eye"></i></a>
                @can('update', $task)
                <a href="{{ route('projects.tasks.edit', [$project, $task]) }}" class="text-gray-500 hover:text-gray-700 text-sm mr-3"><i class="fas fa-pencil"></i></a>
                @endcan
                @can('delete', $task)
                <form action="{{ route('projects.tasks.destroy', [$project, $task]) }}" method="POST" class="inline" onsubmit="return confirm('Delete task?')">
                    @csrf @method('DELETE')
                    <button class="text-red-400 hover:text-red-600 text-sm"><i class="fas fa-trash"></i></button>
                </form>
                @endcan
            </td>
        </tr>
        @empty
        <tr><td colspan="6" class="px-5 py-12 text-center text-gray-400">
            <i class="fas fa-tasks text-4xl mb-3 block"></i>No tasks yet.
            <a href="{{ route('projects.tasks.create', $project) }}" class="text-indigo-600 hover:underline block mt-2">Create first task</a>
        </td></tr>
        @endforelse
        </tbody>
    </table>
</div>
@endsection
