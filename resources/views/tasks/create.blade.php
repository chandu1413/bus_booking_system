@extends('layouts.app')
@section('title', 'New Task')
@section('page-title', 'Create Task')
@section('breadcrumb')
    {!! '<a href="'.route('projects.index').'" class="text-indigo-600 hover:underline">Projects</a> / <a href="'.route('projects.show', $project).'" class="text-indigo-600 hover:underline">'.e($project->name).'</a> / New Task' !!}
@endsection
@section('content')
<div class="max-w-2xl">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8">
        <form action="{{ route('projects.tasks.store', $project) }}" method="POST">
            @csrf
            <div class="space-y-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Task Title <span class="text-red-500">*</span></label>
                    <input type="text" name="title" value="{{ old('title') }}" required placeholder="What needs to be done?"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 @error('title') border-red-500 @enderror">
                    @error('title')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea name="description" rows="4" placeholder="Add details, context, or acceptance criteria..."
                        class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500">{{ old('description') }}</textarea>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select name="status" class="w-full border border-gray-300 rounded-lg px-4 py-2.5">
                            <option value="todo" selected>To Do</option>
                            <option value="in_progress">In Progress</option>
                            <option value="in_review">In Review</option>
                            <option value="done">Done</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Priority</label>
                        <select name="priority" class="w-full border border-gray-300 rounded-lg px-4 py-2.5">
                            <option value="low">Low</option>
                            <option value="medium" selected>Medium</option>
                            <option value="high">High</option>
                            <option value="urgent">Urgent</option>
                        </select>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Assignee</label>
                        <select name="assignee_id" class="w-full border border-gray-300 rounded-lg px-4 py-2.5">
                            <option value="">Unassigned</option>
                            @foreach($members as $member)
                            <option value="{{ $member->id }}" {{ old('assignee_id') == $member->id ? 'selected' : '' }}>{{ $member->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Due Date</label>
                        <input type="date" name="due_date" value="{{ old('due_date') }}" class="w-full border border-gray-300 rounded-lg px-4 py-2.5">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Estimated Hours</label>
                    <input type="number" name="estimated_hours" value="{{ old('estimated_hours') }}" step="0.5" min="0" placeholder="e.g. 8"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2.5">
                </div>
            </div>
            <div class="flex justify-end space-x-3 mt-6 pt-6 border-t border-gray-100">
                <a href="{{ route('projects.show', $project) }}" class="px-5 py-2.5 border border-gray-300 text-gray-700 rounded-lg text-sm">Cancel</a>
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-lg text-sm font-medium">
                    <i class="fas fa-plus mr-2"></i>Create Task
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
