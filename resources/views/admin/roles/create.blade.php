@extends('layouts.app')
@section('title', 'Create Role')
@section('page-title', 'Create Role')
@section('content')
<div class="max-w-xl">
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-8">
        <form action="{{ route('admin.roles.store') }}" method="POST">
            @csrf
            <div class="mb-5">
                <label class="block text-sm font-medium text-gray-700 mb-1">Role Name</label>
                <input type="text" name="name" value="{{ old('name') }}" required placeholder="e.g. ProjectManager"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2.5">
                @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Permissions</label>
                <div class="space-y-4">
                    @foreach($permissions as $group => $perms)
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-sm font-semibold text-gray-700 mb-2 capitalize">{{ $group }}</p>
                        <div class="space-y-2">
                            @foreach($perms as $perm)
                            <label class="flex items-center space-x-2">
                                <input type="checkbox" name="permissions[]" value="{{ $perm->id }}" class="rounded">
                                <span class="text-sm text-gray-600">{{ $perm->name }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            <div class="flex justify-end space-x-3 mt-6 pt-6 border-t border-gray-100">
                <a href="{{ route('admin.roles.index') }}" class="px-5 py-2.5 border border-gray-300 text-gray-700 rounded-lg text-sm">Cancel</a>
                <button type="submit" class="bg-indigo-600 text-white px-5 py-2.5 rounded-lg text-sm hover:bg-indigo-700">Create Role</button>
            </div>
        </form>
    </div>
</div>
@endsection
