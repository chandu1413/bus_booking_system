@extends('layouts.app')
@section('title', 'Buses')
@section('page-title', 'Buses')
@section('content')
<div class="flex items-center justify-between mb-5">
    <p class="text-sm text-gray-500">{{ $buses->count() }} buses configured</p>
    <a href="{{ route('operator.buses.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm">
        <i class="fas fa-plus mr-2"></i>New Buses
    </a>
</div>
<div class="space-y-4">
    @foreach($buses as $bus)
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
        <div class="flex items-start justify-between">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-shield-alt text-indigo-600"></i>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-800">{{ $bus->name }}</h3>
                    <p class="text-xs text-gray-500">{{ $bus->capacity }} seats</p>
                </div>
            </div>
            <div class="flex space-x-2">
                <a href="{{ route('admin.roles.edit', $role) }}" class="px-3 py-1.5 text-sm border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50"><i class="fas fa-pencil"></i></a>
                @if(!in_array($role->name, ['SuperAdmin', 'Admin', 'Member']))
                <form action="{{ route('admin.roles.destroy', $role) }}" method="POST" onsubmit="return confirm('Delete role?')">
                    @csrf @method('DELETE')
                    <button class="px-3 py-1.5 text-sm border border-red-200 text-red-600 rounded-lg hover:bg-red-50"><i class="fas fa-trash"></i></button>
                </form>
                @endif
            </div>
        </div>
        @if($role->permissions->count())
        <div class="flex flex-wrap gap-2 mt-3">
            @foreach($role->permissions as $perm)
            <span class="text-xs bg-indigo-50 text-indigo-700 px-2 py-1 rounded-full">{{ $perm->name }}</span>
            @endforeach
        </div>
        @endif
    </div>
    @endforeach
</div>
@endsection
