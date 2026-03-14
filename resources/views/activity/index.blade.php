@extends('layouts.app')
@section('title', 'Activity Log')
@section('page-title', 'Activity Log')
@section('breadcrumb', 'Recent actions and changes across your projects')
@section('content')
<div class="bg-white rounded-xl border border-gray-100 shadow-sm">
    <div class="p-5 border-b border-gray-100">
        <h2 class="font-semibold text-gray-800">All Activity</h2>
    </div>
    <div class="divide-y divide-gray-100">
        @forelse($logs as $log)
        <div class="flex items-start space-x-4 px-5 py-4 hover:bg-gray-50">
            <img src="{{ $log->user?->avatar_url ?? 'https://ui-avatars.com/api/?name=System' }}" class="w-9 h-9 rounded-full flex-shrink-0">
            <div class="flex-1">
                <p class="text-sm text-gray-700">
                    <span class="font-medium">{{ $log->user?->name ?? 'System' }}</span>
                    {{ $log->description }}
                </p>
                @if($log->loggable)
                <p class="text-xs text-gray-500 mt-0.5">
                    <i class="fas fa-tag mr-1"></i>
                    @if(str_contains($log->loggable_type, 'Project'))Project: {{ $log->loggable->name ?? 'Deleted' }}
                    @elseif(str_contains($log->loggable_type, 'Task'))Task: {{ $log->loggable->title ?? 'Deleted' }}
                    @endif
                </p>
                @endif
            </div>
            <div class="text-right">
                <span class="text-xs text-gray-400">{{ $log->created_at->diffForHumans() }}</span>
                <span class="block text-xs bg-gray-100 text-gray-600 px-2 py-0.5 rounded-full mt-1">{{ str_replace('_', ' ', $log->action) }}</span>
            </div>
        </div>
        @empty
        <div class="px-5 py-12 text-center text-gray-400">
            <i class="fas fa-history text-5xl mb-4 block"></i>
            No activity logged yet.
        </div>
        @endforelse
    </div>
    <div class="p-5 border-t border-gray-100">{{ $logs->links() }}</div>
</div>
@endsection
