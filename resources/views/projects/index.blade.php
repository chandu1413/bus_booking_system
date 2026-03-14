@extends('layouts.app')

@section('title', 'Projects')
@section('page-title', 'Projects')
@section('breadcrumb', 'Manage and track all your projects')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div class="flex space-x-2">
        <a href="?status=Active" class="px-4 py-2 text-sm rounded-lg {{ request('status') === 'Active' ? 'bg-green-100 text-green-700 font-medium' : 'bg-white text-gray-600 border border-gray-200' }}">Active</a>
        <a href="?status=OnHold" class="px-4 py-2 text-sm rounded-lg {{ request('status') === 'OnHold' ? 'bg-yellow-100 text-yellow-700 font-medium' : 'bg-white text-gray-600 border border-gray-200' }}">On Hold</a>
        <a href="?status=Completed" class="px-4 py-2 text-sm rounded-lg {{ request('status') === 'Completed' ? 'bg-blue-100 text-blue-700 font-medium' : 'bg-white text-gray-600 border border-gray-200' }}">Completed</a>
        <a href="{{ route('projects.index') }}" class="px-4 py-2 text-sm rounded-lg bg-white text-gray-600 border border-gray-200">All</a>
    </div>
    @can('create', App\Models\Project::class)
    <a href="{{ route('projects.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
        <i class="fas fa-plus mr-2"></i>New Project
    </a>
    @endcan
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5 mb-8">
    @forelse($projects as $project)
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow">
        <div class="h-2 rounded-t-xl" style="background: {{ $project->color }}"></div>
        <div class="p-5">
            <div class="flex items-start justify-between mb-2">
                <h3 class="font-semibold text-gray-800">{{ $project->name }}</h3>
                <span class="text-xs px-2 py-1 rounded-full font-medium flex-shrink-0 ml-2
                    @if($project->status === 'Active') bg-green-100 text-green-700
                    @elseif($project->status === 'OnHold') bg-yellow-100 text-yellow-700
                    @else bg-blue-100 text-blue-700 @endif">{{ $project->status }}</span>
            </div>
            <p class="text-sm text-gray-500 mb-4 line-clamp-2">{{ $project->description ?? 'No description provided.' }}</p>

            <div class="mb-3">
                <div class="flex justify-between text-xs text-gray-500 mb-1">
                    <span>{{ $project->tasks->where('status', 'done')->count() }} / {{ $project->tasks->count() }} tasks done</span>
                    <span class="font-medium">{{ $project->completion_percentage }}%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="h-2 rounded-full" style="width: {{ $project->completion_percentage }}%; background: {{ $project->color }}"></div>
                </div>
            </div>

            <div class="flex items-center justify-between">
                <div class="flex -space-x-2">
                    @foreach($project->members->take(4) as $member)
                    <img src="{{ $member->avatar_url }}" class="w-7 h-7 rounded-full border-2 border-white" title="{{ $member->name }}">
                    @endforeach
                    @if($project->members->count() > 4)
                    <div class="w-7 h-7 rounded-full bg-gray-200 border-2 border-white flex items-center justify-center text-xs text-gray-600">+{{ $project->members->count() - 4 }}</div>
                    @endif
                </div>
                <div class="flex space-x-2">
                    <a href="{{ route('projects.show', $project) }}" class="text-indigo-600 hover:text-indigo-800 text-sm"><i class="fas fa-eye"></i></a>
                    @can('update', $project)
                    <a href="{{ route('projects.edit', $project) }}" class="text-gray-500 hover:text-gray-700 text-sm"><i class="fas fa-pencil"></i></a>
                    @endcan
                    @can('delete', $project)
                    <form action="{{ route('projects.destroy', $project) }}" method="POST" class="inline" onsubmit="return confirm('Move to trash?')">
                        @csrf @method('DELETE')
                        <button class="text-red-400 hover:text-red-600 text-sm"><i class="fas fa-trash"></i></button>
                    </form>
                    @endcan
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="col-span-3 bg-white rounded-xl border border-dashed border-gray-300 p-12 text-center">
        <i class="fas fa-folder-plus text-gray-300 text-5xl mb-4"></i>
        <p class="text-gray-500 text-lg mb-4">No projects found</p>
        @can('create', App\Models\Project::class)
        <a href="{{ route('projects.create') }}" class="bg-indigo-600 text-white px-6 py-2.5 rounded-lg hover:bg-indigo-700">Create your first project</a>
        @endcan
    </div>
    @endforelse
</div>

{{ $projects->links() }}

@if(count($trashedProjects) > 0)
<div class="mt-8">
    <h2 class="text-lg font-semibold text-gray-700 mb-4"><i class="fas fa-trash text-red-400 mr-2"></i>Trashed Projects</h2>
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        <table class="min-w-full">
            <thead class="bg-gray-50"><tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Owner</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Deleted</th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
            </tr></thead>
            <tbody>
            @foreach($trashedProjects as $tp)
            <tr class="border-t border-gray-100">
                <td class="px-6 py-3 text-sm text-gray-600 line-through">{{ $tp->name }}</td>
                <td class="px-6 py-3 text-sm text-gray-600">{{ $tp->owner->name }}</td>
                <td class="px-6 py-3 text-sm text-gray-400">{{ $tp->deleted_at->diffForHumans() }}</td>
                <td class="px-6 py-3 text-right">
                    <form action="{{ route('projects.restore', $tp->id) }}" method="POST" class="inline">
                        @csrf @method('PATCH')
                        <button class="text-green-600 hover:underline text-sm">Restore</button>
                    </form>
                </td>
            </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif
@endsection
