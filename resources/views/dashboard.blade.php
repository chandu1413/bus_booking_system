@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('breadcrumb', 'Welcome back, ' . auth()->user()->name . '!')

@section('content')
<!-- KPI Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-5 mb-8">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs text-gray-500 font-medium uppercase tracking-wider">Total Projects</p>
                <p class="text-3xl font-bold text-gray-800 mt-1">{{ $stats['total_projects'] }}</p>
            </div>
            <div class="w-12 h-12 bg-indigo-100 rounded-xl flex items-center justify-center">
                <i class="fas fa-folder-open text-indigo-600 text-lg"></i>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs text-gray-500 font-medium uppercase tracking-wider">Active Projects</p>
                <p class="text-3xl font-bold text-green-600 mt-1">{{ $stats['active_projects'] }}</p>
            </div>
            <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                <i class="fas fa-play-circle text-green-600 text-lg"></i>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs text-gray-500 font-medium uppercase tracking-wider">My Tasks</p>
                <p class="text-3xl font-bold text-blue-600 mt-1">{{ $stats['my_tasks'] }}</p>
            </div>
            <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                <i class="fas fa-tasks text-blue-600 text-lg"></i>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs text-gray-500 font-medium uppercase tracking-wider">Completed</p>
                <p class="text-3xl font-bold text-purple-600 mt-1">{{ $stats['completed_tasks'] }}</p>
            </div>
            <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                <i class="fas fa-check-double text-purple-600 text-lg"></i>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs text-gray-500 font-medium uppercase tracking-wider">Overdue</p>
                <p class="text-3xl font-bold text-red-600 mt-1">{{ $stats['overdue_tasks'] }}</p>
            </div>
            <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center">
                <i class="fas fa-exclamation-triangle text-red-600 text-lg"></i>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Projects Grid -->
    <div class="lg:col-span-2">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-semibold text-gray-800">Recent Projects</h2>
            <a href="{{ route('projects.index') }}" class="text-sm text-indigo-600 hover:underline">View all</a>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @forelse($projects as $project)
            <a href="{{ route('projects.show', $project) }}" class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 hover:shadow-md transition-shadow block">
                <div class="flex items-start justify-between mb-3">
                    <div class="flex items-center space-x-3">
                        <div class="w-3 h-3 rounded-full" style="background: {{ $project->color }}"></div>
                        <h3 class="font-semibold text-gray-800 text-sm">{{ Str::limit($project->name, 30) }}</h3>
                    </div>
                    <span class="text-xs px-2 py-1 rounded-full font-medium
                        @if($project->status === 'Active') bg-green-100 text-green-700
                        @elseif($project->status === 'OnHold') bg-yellow-100 text-yellow-700
                        @else bg-blue-100 text-blue-700 @endif">{{ $project->status }}</span>
                </div>
                <div class="mb-3">
                    <div class="flex justify-between text-xs text-gray-500 mb-1">
                        <span>Progress</span>
                        <span>{{ $project->completion_percentage }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="h-2 rounded-full transition-all" style="width: {{ $project->completion_percentage }}%; background: {{ $project->color }}"></div>
                    </div>
                </div>
                <div class="flex items-center justify-between text-xs text-gray-500">
                    <span><i class="fas fa-tasks mr-1"></i>{{ $project->tasks->count() }} tasks</span>
                    @if($project->end_date)
                    <span><i class="fas fa-calendar mr-1"></i>{{ $project->end_date->format('M d') }}</span>
                    @endif
                </div>
            </a>
            @empty
            <div class="col-span-2 bg-white rounded-xl border border-dashed border-gray-300 p-8 text-center">
                <i class="fas fa-folder-plus text-gray-300 text-4xl mb-3"></i>
                <p class="text-gray-500 mb-4">No projects yet</p>
                <a href="{{ route('projects.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-indigo-700">Create First Project</a>
            </div>
            @endforelse
        </div>
    </div>

    <!-- My Tasks + Activity -->
    <div class="space-y-6">
        <!-- My Tasks -->
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-gray-800">My Tasks</h2>
                <span class="text-xs bg-indigo-100 text-indigo-700 px-2 py-1 rounded-full">{{ $myTasks->count() }} pending</span>
            </div>
            <div class="space-y-3">
                @forelse($myTasks->take(6) as $task)
                <a href="{{ route('projects.tasks.show', [$task->project_id, $task->id]) }}" class="block">
                    <div class="flex items-start space-x-3 p-2 hover:bg-gray-50 rounded-lg transition-colors">
                        <div class="w-2 h-2 mt-1.5 rounded-full flex-shrink-0
                            @if($task->priority === 'urgent') bg-red-500
                            @elseif($task->priority === 'high') bg-orange-500
                            @elseif($task->priority === 'medium') bg-blue-500
                            @else bg-gray-400 @endif"></div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-700 truncate {{ $task->is_overdue ? 'text-red-600' : '' }}">{{ $task->title }}</p>
                            <p class="text-xs text-gray-400">{{ $task->project->name }}</p>
                        </div>
                        @if($task->due_date)
                        <span class="text-xs {{ $task->is_overdue ? 'text-red-500 font-medium' : 'text-gray-400' }} flex-shrink-0">{{ $task->due_date->format('M d') }}</span>
                        @endif
                    </div>
                </a>
                @empty
                <p class="text-gray-400 text-sm text-center py-4"><i class="fas fa-check-circle text-green-400 mr-1"></i>All caught up!</p>
                @endforelse
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Recent Activity</h2>
            <div class="space-y-3">
                @foreach($recentActivity as $log)
                <div class="flex items-start space-x-3">
                    <img src="{{ $log->user?->avatar_url }}" class="w-7 h-7 rounded-full flex-shrink-0" alt="">
                    <div class="flex-1 min-w-0">
                        <p class="text-xs text-gray-600 leading-relaxed"><span class="font-medium">{{ $log->user?->name ?? 'System' }}</span> {{ $log->description }}</p>
                        <p class="text-xs text-gray-400 mt-0.5">{{ $log->created_at->diffForHumans() }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
