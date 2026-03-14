@extends('layouts.app')
@section('title', 'Create User')
@section('page-title', 'Create User')
@section('content')
<div class="max-w-xl">
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-8">
        <form action="{{ route('admin.users.store') }}" method="POST">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                    <input type="text" name="name" value="{{ old('name') }}" required class="w-full border border-gray-300 rounded-lg px-4 py-2.5">
                    @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required class="w-full border border-gray-300 rounded-lg px-4 py-2.5">
                    @error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <input type="password" name="password" required minlength="8" class="w-full border border-gray-300 rounded-lg px-4 py-2.5">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
                    <input type="password" name="password_confirmation" required class="w-full border border-gray-300 rounded-lg px-4 py-2.5">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Roles</label>
                    <div class="space-y-2">
                        @foreach($roles as $role)
                        <label class="flex items-center space-x-2">
                            <input type="checkbox" name="roles[]" value="{{ $role->id }}" class="rounded">
                            <span class="text-sm">{{ $role->name }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>
                <div>
                    <label class="flex items-center space-x-2">
                        <input type="checkbox" name="is_active" value="1" checked class="rounded">
                        <span class="text-sm font-medium text-gray-700">Active Account</span>
                    </label>
                </div>
            </div>
            <div class="flex justify-end space-x-3 mt-6 pt-6 border-t border-gray-100">
                <a href="{{ route('admin.users.index') }}" class="px-5 py-2.5 border border-gray-300 text-gray-700 rounded-lg text-sm">Cancel</a>
                <button type="submit" class="bg-indigo-600 text-white px-5 py-2.5 rounded-lg text-sm hover:bg-indigo-700">Create User</button>
            </div>
        </form>
    </div>
</div>
@endsection
