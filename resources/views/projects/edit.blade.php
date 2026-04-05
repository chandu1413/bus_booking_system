@extends('layouts.app')
@section('title', 'Edit Project')
@section('page-title', 'Edit Project')
@section('breadcrumb')
    {!! '<a href="'.route('projects.index').'" class="text-indigo-600 hover:underline">Projects</a> / <a href="'.route('projects.show', $project).'" class="text-indigo-600 hover:underline">'.e($project->name).'</a> / Edit' !!}
@endsection
@section('content')
<div class="max-w-2xl">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8">
        <form action="{{ route('projects.update', $project) }}" method="POST">
            @csrf @method('PUT')
            <div class="grid grid-cols-1 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Project Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $project->name) }}" required
                        class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 @error('name') border-red-500 @enderror">
                    @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea name="description" rows="4" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500">{{ old('description', $project->description) }}</textarea>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select name="status" class="w-full border border-gray-300 rounded-lg px-4 py-2.5">
                            @foreach(['Active', 'OnHold', 'Completed'] as $s)
                            <option value="{{ $s }}" {{ old('status', $project->status) === $s ? 'selected' : '' }}>{{ $s === 'OnHold' ? 'On Hold' : $s }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Color</label>
                        <input type="color" name="color" value="{{ old('color', $project->color) }}" class="w-full h-10 border border-gray-300 rounded-lg px-1 cursor-pointer">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Start Date</label>
                        <input type="date" name="start_date" value="{{ old('start_date', $project->start_date?->format('Y-m-d')) }}" class="w-full border border-gray-300 rounded-lg px-4 py-2.5">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
                        <input type="date" name="end_date" value="{{ old('end_date', $project->end_date?->format('Y-m-d')) }}" class="w-full border border-gray-300 rounded-lg px-4 py-2.5">
                        @error('end_date')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>
            <div class="flex justify-between mt-6 pt-6 border-t border-gray-100">
                <a href="{{ route('projects.show', $project) }}" class="px-5 py-2.5 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 text-sm">Cancel</a>
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-lg text-sm font-medium">Save Changes</button>
            </div>
        </form>
    </div>
</div>
@endsection
