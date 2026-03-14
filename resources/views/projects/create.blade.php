@extends('layouts.app')
@section('title', 'New Project')
@section('page-title', 'Create New Project')
@section('breadcrumb', '<a href="'.route('projects.index').'" class="text-indigo-600 hover:underline">Projects</a> / Create')
@section('content')
<div class="max-w-2xl">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8">
        <form action="{{ route('projects.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Project Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" required placeholder="e.g. Website Redesign 2024"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 @error('name') border-red-500 @enderror">
                    @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea name="description" rows="4" placeholder="Describe the project goals and scope..."
                        class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500">{{ old('description') }}</textarea>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status <span class="text-red-500">*</span></label>
                        <select name="status" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500">
                            <option value="Active" {{ old('status') === 'Active' ? 'selected' : '' }}>Active</option>
                            <option value="OnHold" {{ old('status') === 'OnHold' ? 'selected' : '' }}>On Hold</option>
                            <option value="Completed" {{ old('status') === 'Completed' ? 'selected' : '' }}>Completed</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Project Color</label>
                        <input type="color" name="color" value="{{ old('color', '#6366f1') }}"
                            class="w-full h-10 border border-gray-300 rounded-lg px-1 cursor-pointer">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Start Date</label>
                        <input type="date" name="start_date" value="{{ old('start_date') }}"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
                        <input type="date" name="end_date" value="{{ old('end_date') }}"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500">
                        @error('end_date')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>
            <div class="flex justify-end space-x-3 mt-6 pt-6 border-t border-gray-100">
                <a href="{{ route('projects.index') }}" class="px-5 py-2.5 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 text-sm">Cancel</a>
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-lg text-sm font-medium">
                    <i class="fas fa-plus mr-2"></i>Create Project
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
