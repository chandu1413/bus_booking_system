@extends('layouts.app')
@section('title', 'Admin Dashboard')
@section('page-title', 'Admin Dashboard')
@section('breadcrumb', 'Platform-wide analytics and system health')
@section('content')
<!-- Stats Grid -->
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
    @php
    $cards = [
        ['Total Users', $stats['total_users'], 'fa-users', 'indigo'],
        ['Active Projects', $stats['active_projects'], 'fa-folder-open', 'green'],
        ['Total Tasks', $stats['total_tasks'], 'fa-tasks', 'blue'],
        ['Overdue Tasks', $stats['overdue_tasks'], 'fa-exclamation-triangle', 'red'],
    ];
    @endphp
    @foreach($cards as [$label, $value, $icon, $color])
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs text-gray-500 font-medium uppercase tracking-wider">{{ $label }}</p>
                <p class="text-3xl font-bold text-gray-800 mt-1">{{ $value }}</p>
            </div>
            <div class="w-12 h-12 bg-{{ $color }}-100 rounded-xl flex items-center justify-center">
                <i class="fas {{ $icon }} text-{{ $color }}-600 text-lg"></i>
            </div>
        </div>
    </div>
    @endforeach
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Task Status Breakdown -->
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
        <h3 class="font-semibold text-gray-800 mb-4">Task Status Breakdown</h3>
        <div class="space-y-3">
            @php
            $statusData = [
                'todo'        => ['To Do', 'gray', $stats['tasks_todo']],
                'in_progress' => ['In Progress', 'blue', $stats['tasks_in_progress']],
                'in_review'   => ['In Review', 'purple', $stats['tasks_in_review']],
                'done'        => ['Done', 'green', $stats['completed_tasks']],
            ];
            $totalTasks = max($stats['total_tasks'], 1);
            @endphp
            @foreach($statusData as [$label, $color, $count])
            <div>
                <div class="flex justify-between text-sm mb-1">
                    <span class="text-gray-600">{{ $label }}</span>
                    <span class="font-medium text-gray-800">{{ $count }}</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="h-2 bg-{{ $color }}-500 rounded-full" style="width: {{ round(($count / $totalTasks) * 100) }}%"></div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Priority Distribution -->
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
        <h3 class="font-semibold text-gray-800 mb-4">Tasks by Priority</h3>
        <div class="space-y-3">
            @foreach(['urgent' => ['Urgent', 'red'], 'high' => ['High', 'orange'], 'medium' => ['Medium', 'blue'], 'low' => ['Low', 'gray']] as $p => [$pl, $pc])
            @php $cnt = $tasksByPriority[$p] ?? 0; @endphp
            <div>
                <div class="flex justify-between text-sm mb-1">
                    <span class="text-gray-600">{{ $pl }}</span>
                    <span class="font-medium">{{ $cnt }}</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="h-2 bg-{{ $pc }}-500 rounded-full" style="width: {{ $totalTasks > 0 ? round(($cnt / $totalTasks) * 100) : 0 }}%"></div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Top Performers -->
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
        <h3 class="font-semibold text-gray-800 mb-4">Top Performers</h3>
        <div class="space-y-3">
            @forelse($topUsers as $i => $user)
            <div class="flex items-center space-x-3">
                <span class="text-lg font-bold text-gray-300 w-5">{{ $i + 1 }}</span>
                <img src="{{ $user->avatar_url }}" class="w-8 h-8 rounded-full">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-800">{{ $user->name }}</p>
                    <p class="text-xs text-gray-400">{{ $user->completed_tasks }} tasks completed</p>
                </div>
                <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-medal text-green-600 text-xs"></i>
                </div>
            </div>
            @empty
            <p class="text-sm text-gray-400">No data available.</p>
            @endforelse
        </div>
    </div>
</div>

<!-- Project Stats Row -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6">
    <div class="bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl p-5 text-white">
        <p class="text-green-100 text-sm font-medium">Active Projects</p>
        <p class="text-4xl font-bold mt-1">{{ $stats['active_projects'] }}</p>
    </div>
    <div class="bg-gradient-to-br from-yellow-500 to-orange-500 rounded-xl p-5 text-white">
        <p class="text-yellow-100 text-sm font-medium">On Hold</p>
        <p class="text-4xl font-bold mt-1">{{ $stats['on_hold_projects'] }}</p>
    </div>
    <div class="bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl p-5 text-white">
        <p class="text-blue-100 text-sm font-medium">Completed</p>
        <p class="text-4xl font-bold mt-1">{{ $stats['completed_projects'] }}</p>
    </div>
</div>

<!-- Recent Activity -->
<div class="bg-white rounded-xl border border-gray-100 shadow-sm mt-6">
    <div class="flex items-center justify-between p-5 border-b border-gray-100">
        <h3 class="font-semibold text-gray-800">Recent Activity</h3>
        <a href="{{ route('activity.index') }}" class="text-sm text-indigo-600 hover:underline">View all</a>
    </div>
    <div class="divide-y divide-gray-100">
        @foreach($recentActivity as $log)
        <div class="flex items-center space-x-4 px-5 py-3">
            <img src="{{ $log->user?->avatar_url ?? 'https://ui-avatars.com/api/?name=System' }}" class="w-8 h-8 rounded-full">
            <div class="flex-1">
                <p class="text-sm text-gray-700"><span class="font-medium">{{ $log->user?->name ?? 'System' }}</span> {{ $log->description }}</p>
            </div>
            <span class="text-xs text-gray-400">{{ $log->created_at->diffForHumans() }}</span>
        </div>
        @endforeach
    </div>
</div>
@endsection
