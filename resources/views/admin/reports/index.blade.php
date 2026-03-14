@extends('layouts.app')
@section('title', 'Reports')
@section('page-title', 'Analytics & Reports')
@section('breadcrumb', 'View project and task performance metrics')
@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <!-- Project Status -->
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
        <h3 class="font-semibold text-gray-800 mb-4">Project Status Distribution</h3>
        @php $totalProjects = max(array_sum($projectStats->toArray()), 1); @endphp
        @foreach(['Active' => 'green', 'OnHold' => 'yellow', 'Completed' => 'blue'] as $status => $color)
        @php $count = $projectStats[$status] ?? 0; $pct = round(($count / $totalProjects) * 100); @endphp
        <div class="mb-3">
            <div class="flex justify-between text-sm mb-1">
                <span class="text-gray-600">{{ $status === 'OnHold' ? 'On Hold' : $status }}</span>
                <span class="font-medium">{{ $count }} ({{ $pct }}%)</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-3">
                <div class="h-3 bg-{{ $color }}-500 rounded-full" style="width: {{ $pct }}%"></div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Task Status -->
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
        <h3 class="font-semibold text-gray-800 mb-4">Task Status Distribution</h3>
        @php $totalT = max(array_sum($taskStats->toArray()), 1); @endphp
        @foreach(['todo' => ['To Do', 'gray'], 'in_progress' => ['In Progress', 'blue'], 'in_review' => ['In Review', 'purple'], 'done' => ['Done', 'green']] as $s => [$sl, $sc])
        @php $cnt = $taskStats[$s] ?? 0; $pct = round(($cnt / $totalT) * 100); @endphp
        <div class="mb-3">
            <div class="flex justify-between text-sm mb-1">
                <span class="text-gray-600">{{ $sl }}</span>
                <span class="font-medium">{{ $cnt }} ({{ $pct }}%)</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-3">
                <div class="h-3 bg-{{ $sc }}-500 rounded-full" style="width: {{ $pct }}%"></div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Monthly Tasks -->
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
        <h3 class="font-semibold text-gray-800 mb-4">Monthly Task Creation vs Completion ({{ date('Y') }})</h3>
        @php $months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec']; @endphp
        <div class="space-y-2">
            @foreach($monthlyTasks as $row)
            @php $pct = $row->total > 0 ? round(($row->completed / $row->total) * 100) : 0; @endphp
            <div>
                <div class="flex justify-between text-xs text-gray-600 mb-1">
                    <span>{{ $months[$row->month - 1] }}</span>
                    <span>{{ $row->completed }}/{{ $row->total }} completed ({{ $pct }}%)</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="h-2 bg-indigo-500 rounded-full" style="width: {{ $pct }}%"></div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- User Performance -->
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
        <h3 class="font-semibold text-gray-800 mb-4">User Performance Leaderboard</h3>
        <div class="space-y-3">
            @forelse($userPerformance as $i => $user)
            @php $rate = $user->assigned_tasks > 0 ? round(($user->completed_tasks / $user->assigned_tasks) * 100) : 0; @endphp
            <div class="flex items-center space-x-3">
                <span class="text-sm font-bold text-gray-400 w-5">{{ $i + 1 }}</span>
                <img src="{{ $user->avatar_url }}" class="w-8 h-8 rounded-full">
                <div class="flex-1">
                    <div class="flex justify-between text-sm mb-0.5">
                        <span class="font-medium text-gray-800">{{ $user->name }}</span>
                        <span class="text-gray-500">{{ $user->completed_tasks }}/{{ $user->assigned_tasks }}</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-1.5">
                        <div class="h-1.5 bg-green-500 rounded-full" style="width: {{ $rate }}%"></div>
                    </div>
                </div>
                <span class="text-xs font-medium text-gray-600">{{ $rate }}%</span>
            </div>
            @empty
            <p class="text-sm text-gray-400">No data available.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
