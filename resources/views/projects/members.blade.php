@extends('layouts.app')
@section('title', 'Project Members')
@section('page-title', 'Project Members')
@section('breadcrumb', '<a href="'.route('projects.index').'" class="text-indigo-600 hover:underline">Projects</a> / <a href="'.route('projects.show', $project).'" class="text-indigo-600 hover:underline">'.e($project->name).'</a> / Members')
@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2">
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm">
            <div class="p-5 border-b border-gray-100">
                <h2 class="font-semibold text-gray-800">Current Members ({{ $project->members->count() }})</h2>
            </div>
            <div class="divide-y divide-gray-100">
                @foreach($project->members as $member)
                <div class="flex items-center justify-between px-5 py-3">
                    <div class="flex items-center space-x-3">
                        <img src="{{ $member->avatar_url }}" class="w-9 h-9 rounded-full">
                        <div>
                            <p class="text-sm font-medium text-gray-800">{{ $member->name }}</p>
                            <p class="text-xs text-gray-500">{{ $member->email }}</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-3">
                        <span class="text-xs px-2 py-1 bg-gray-100 text-gray-700 rounded-full capitalize">{{ $member->pivot->role }}</span>
                        @if($member->id !== $project->owner_id)
                        @can('manageMembers', $project)
                        <form action="{{ route('projects.members.destroy', [$project, $member]) }}" method="POST" onsubmit="return confirm('Remove member?')">
                            @csrf @method('DELETE')
                            <button class="text-red-400 hover:text-red-600 text-sm"><i class="fas fa-times"></i></button>
                        </form>
                        @endcan
                        @else
                        <span class="text-xs text-indigo-600 font-medium">Owner</span>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    @can('manageMembers', $project)
    <div>
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
            <h2 class="font-semibold text-gray-800 mb-4">Add Member</h2>
            <form action="{{ route('projects.members.store', $project) }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Select User</label>
                    <select name="user_id" required class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500">
                        <option value="">Choose a user...</option>
                        @foreach($availableUsers as $user)
                        <option value="{{ $user->id }}">{{ $user->name }} — {{ $user->email }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                    <select name="role" class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm">
                        <option value="member">Member</option>
                        <option value="lead">Lead</option>
                        <option value="reviewer">Reviewer</option>
                    </select>
                </div>
                <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white py-2.5 rounded-lg text-sm font-medium">
                    <i class="fas fa-user-plus mr-2"></i>Add to Project
                </button>
            </form>
        </div>
    </div>
    @endcan
</div>
@endsection
