@extends('layouts.app')
@section('title', $task->title)
@section('page-title', $task->title)
@section('breadcrumb')
    {!! '<a href="'.route('projects.show', $project).'" class="text-indigo-600 hover:underline">'.e($project->name).'</a> / '.e($task->title) !!}
@endsection
@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Main Content -->
    <div class="lg:col-span-2 space-y-5">
        <!-- Task Details -->
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
            <div class="flex items-start justify-between mb-4">
                <div class="flex items-center space-x-2">
                    <span class="px-3 py-1 text-sm rounded-full font-medium
                        @if($task->status === 'done') bg-green-100 text-green-700
                        @elseif($task->status === 'in_progress') bg-blue-100 text-blue-700
                        @elseif($task->status === 'in_review') bg-purple-100 text-purple-700
                        @else bg-gray-100 text-gray-700 @endif">{{ $task->status_label }}</span>
                    <span class="px-3 py-1 text-sm rounded-full font-medium
                        @if($task->priority === 'urgent') bg-red-100 text-red-700
                        @elseif($task->priority === 'high') bg-orange-100 text-orange-700
                        @elseif($task->priority === 'medium') bg-blue-100 text-blue-700
                        @else bg-gray-100 text-gray-600 @endif">{{ ucfirst($task->priority) }}</span>
                    @if($task->is_overdue)<span class="px-3 py-1 text-sm rounded-full bg-red-50 text-red-600 font-medium"><i class="fas fa-exclamation-triangle mr-1"></i>Overdue</span>@endif
                </div>
                @can('update', $task)
                <div class="flex space-x-2">
                    <a href="{{ route('projects.tasks.edit', [$project, $task]) }}" class="px-3 py-1.5 text-sm border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50"><i class="fas fa-pencil mr-1"></i>Edit</a>
                    @can('delete', $task)
                    <form action="{{ route('projects.tasks.destroy', [$project, $task]) }}" method="POST" onsubmit="return confirm('Delete this task?')">
                        @csrf @method('DELETE')
                        <button class="px-3 py-1.5 text-sm border border-red-200 text-red-600 rounded-lg hover:bg-red-50"><i class="fas fa-trash mr-1"></i>Delete</button>
                    </form>
                    @endcan
                </div>
                @endcan
            </div>
            @if($task->description)
            <div class="prose prose-sm max-w-none text-gray-600 mb-4">
                <p>{{ $task->description }}</p>
            </div>
            @endif
            <!-- Quick Status Change -->
            @can('update', $task)
            <form action="{{ route('projects.tasks.status', [$project, $task]) }}" method="POST" class="flex space-x-2">
                @csrf @method('PATCH')
                <select name="status" class="text-sm border border-gray-300 rounded-lg px-3 py-1.5">
                    @foreach(['todo' => 'To Do', 'in_progress' => 'In Progress', 'in_review' => 'In Review', 'done' => 'Done'] as $val => $lbl)
                    <option value="{{ $val }}" {{ $task->status === $val ? 'selected' : '' }}>{{ $lbl }}</option>
                    @endforeach
                </select>
                <button type="submit" class="text-sm bg-indigo-600 text-white px-3 py-1.5 rounded-lg hover:bg-indigo-700">Update Status</button>
            </form>
            @endcan
        </div>

        <!-- Subtasks -->
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
            <h3 class="font-semibold text-gray-800 mb-4"><i class="fas fa-list-check text-indigo-500 mr-2"></i>Subtasks ({{ $task->subtasks->where('is_completed', true)->count() }}/{{ $task->subtasks->count() }})</h3>
            <div class="space-y-2 mb-4">
                @foreach($task->subtasks as $subtask)
                <div class="flex items-center space-x-3 p-2 hover:bg-gray-50 rounded-lg group">
                    <form action="{{ route('subtasks.update', [$task, $subtask]) }}" method="POST" class="flex items-center space-x-3 flex-1">
                        @csrf @method('PATCH')
                        <input type="hidden" name="is_completed" value="{{ $subtask->is_completed ? '0' : '1' }}">
                        <button type="submit" class="w-5 h-5 rounded border-2 flex items-center justify-center flex-shrink-0
                            {{ $subtask->is_completed ? 'bg-indigo-600 border-indigo-600' : 'border-gray-300' }}">
                            @if($subtask->is_completed)<i class="fas fa-check text-white text-xs"></i>@endif
                        </button>
                        <span class="text-sm {{ $subtask->is_completed ? 'line-through text-gray-400' : 'text-gray-700' }}">{{ $subtask->title }}</span>
                    </form>
                    <form action="{{ route('subtasks.destroy', [$task, $subtask]) }}" method="POST" class="opacity-0 group-hover:opacity-100">
                        @csrf @method('DELETE')
                        <button class="text-red-400 hover:text-red-600 text-xs"><i class="fas fa-times"></i></button>
                    </form>
                </div>
                @endforeach
            </div>
            @can('update', $task)
            <form action="{{ route('subtasks.store', $task) }}" method="POST" class="flex space-x-2">
                @csrf
                <input type="text" name="title" placeholder="Add a subtask..." required class="flex-1 border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500">
                <button type="submit" class="bg-indigo-600 text-white px-3 py-2 rounded-lg text-sm hover:bg-indigo-700"><i class="fas fa-plus"></i></button>
            </form>
            @endcan
        </div>

        <!-- Comments -->
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
            <h3 class="font-semibold text-gray-800 mb-4"><i class="fas fa-comments text-indigo-500 mr-2"></i>Comments ({{ $task->comments->count() }})</h3>
            <div class="space-y-4 mb-6">
                @forelse($task->comments as $comment)
                <div class="flex space-x-3">
                    <img src="{{ $comment->user->avatar_url }}" class="w-8 h-8 rounded-full flex-shrink-0">
                    <div class="flex-1 bg-gray-50 rounded-xl p-4">
                        <div class="flex items-center justify-between mb-1">
                            <span class="text-sm font-medium text-gray-800">{{ $comment->user->name }}</span>
                            <div class="flex items-center space-x-2">
                                <span class="text-xs text-gray-400">{{ $comment->created_at->diffForHumans() }}</span>
                                @can('delete', $comment)
                                <form action="{{ route('comments.destroy', $comment) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button class="text-red-400 hover:text-red-600 text-xs"><i class="fas fa-times"></i></button>
                                </form>
                                @endcan
                            </div>
                        </div>
                        <p class="text-sm text-gray-600 leading-relaxed">{{ $comment->body }}</p>
                    </div>
                </div>
                @empty
                <p class="text-sm text-gray-400 text-center py-4">No comments yet. Start the conversation!</p>
                @endforelse
            </div>
            <form action="{{ route('comments.store', $task) }}" method="POST">
                @csrf
                <div class="flex space-x-3">
                    <img src="{{ auth()->user()->avatar_url }}" class="w-8 h-8 rounded-full flex-shrink-0">
                    <div class="flex-1">
                        <textarea name="body" rows="2" placeholder="Write a comment..." required
                            class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 resize-none"></textarea>
                        @error('body')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        <button type="submit" class="mt-2 bg-indigo-600 text-white px-4 py-1.5 rounded-lg text-sm hover:bg-indigo-700">
                            <i class="fas fa-paper-plane mr-1"></i>Post Comment
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Attachments -->
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
            <h3 class="font-semibold text-gray-800 mb-4"><i class="fas fa-paperclip text-indigo-500 mr-2"></i>Attachments ({{ $task->attachments->count() }})</h3>
            @if($task->attachments->count())
            <div class="space-y-2 mb-4">
                @foreach($task->attachments as $att)
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <span class="text-lg">{{ $att->icon }}</span>
                        <div>
                            <p class="text-sm font-medium text-gray-700">{{ $att->file_name }}</p>
                            <p class="text-xs text-gray-400">{{ $att->file_size_formatted }} — {{ $att->created_at->format('M d, Y') }}</p>
                        </div>
                    </div>
                    <div class="flex space-x-2">
                        <a href="{{ route('attachments.download', $att) }}" class="text-indigo-600 hover:text-indigo-800 text-sm"><i class="fas fa-download"></i></a>
                        @can('update', $task)
                        <form action="{{ route('attachments.destroy', $att) }}" method="POST">
                            @csrf @method('DELETE')
                            <button class="text-red-400 hover:text-red-600 text-sm"><i class="fas fa-times"></i></button>
                        </form>
                        @endcan
                    </div>
                </div>
                @endforeach
            </div>
            @endif
            @can('update', $task)
            <form action="{{ route('attachments.store', $task) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="border-2 border-dashed border-gray-300 rounded-xl p-4 text-center hover:border-indigo-400 transition-colors">
                    <input type="file" name="file" id="file-upload" class="hidden" onchange="this.form.submit()">
                    <label for="file-upload" class="cursor-pointer">
                        <i class="fas fa-cloud-upload-alt text-gray-400 text-2xl mb-2"></i>
                        <p class="text-sm text-gray-600">Click to upload a file</p>
                        <p class="text-xs text-gray-400 mt-1">Max 20MB — Images, PDFs, Docs, Spreadsheets, Archives</p>
                    </label>
                </div>
                @error('file')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </form>
            @endcan
        </div>
    </div>

    <!-- Sidebar -->
    <div class="space-y-5">
        <!-- Task Meta -->
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
            <h3 class="font-semibold text-gray-800 mb-4">Task Details</h3>
            <div class="space-y-3 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-500">Assignee</span>
                    <span class="text-gray-800 font-medium">
                        @if($task->assignee)
                        <div class="flex items-center space-x-2">
                            <img src="{{ $task->assignee->avatar_url }}" class="w-5 h-5 rounded-full">
                            <span>{{ $task->assignee->name }}</span>
                        </div>
                        @else Unassigned @endif
                    </span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Created by</span>
                    <span class="text-gray-800">{{ $task->creator->name }}</span>
                </div>
                @if($task->due_date)
                <div class="flex justify-between">
                    <span class="text-gray-500">Due Date</span>
                    <span class="{{ $task->is_overdue ? 'text-red-600 font-medium' : 'text-gray-800' }}">{{ $task->due_date->format('M d, Y') }}</span>
                </div>
                @endif
                @if($task->estimated_hours)
                <div class="flex justify-between">
                    <span class="text-gray-500">Estimate</span>
                    <span class="text-gray-800">{{ $task->estimated_hours }}h</span>
                </div>
                @endif
                <div class="flex justify-between">
                    <span class="text-gray-500">Created</span>
                    <span class="text-gray-800">{{ $task->created_at->format('M d, Y') }}</span>
                </div>
            </div>
        </div>

        <!-- Activity Log -->
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
            <h3 class="font-semibold text-gray-800 mb-4"><i class="fas fa-history text-indigo-500 mr-1"></i> Activity</h3>
            <div class="space-y-3">
                @forelse($task->activityLogs->take(8) as $log)
                <div class="flex items-start space-x-2">
                    <div class="w-6 h-6 rounded-full bg-indigo-100 flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-circle text-indigo-400" style="font-size: 6px"></i>
                    </div>
                    <div>
                        <p class="text-xs text-gray-600"><span class="font-medium">{{ $log->user?->name }}</span> {{ $log->description }}</p>
                        <p class="text-xs text-gray-400">{{ $log->created_at->diffForHumans() }}</p>
                    </div>
                </div>
                @empty
                <p class="text-xs text-gray-400">No activity yet.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
