@extends('layouts.app')
@section('title', 'Users')
@section('page-title', 'User Management')
@section('content')
<div class="flex items-center justify-between mb-5">
    <p class="text-sm text-gray-500">{{ $users->total() }} users total</p>
    <a href="{{ route('admin.users.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm">
        <i class="fas fa-user-plus mr-2"></i>Add User
    </a>
</div>
<div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
    <table class="min-w-full">
        <thead class="bg-gray-50"><tr>
            <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase">User</th>
            <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase">Role</th>
            <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
            <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase">Last Login</th>
            <th class="px-5 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
        </tr></thead>
        <tbody class="divide-y divide-gray-100">
        @foreach($users as $user)
        <tr class="hover:bg-gray-50 {{ $user->deleted_at ? 'opacity-50' : '' }}">
            <td class="px-5 py-3">
                <div class="flex items-center space-x-3">
                    <img src="{{ $user->avatar_url }}" class="w-9 h-9 rounded-full">
                    <div>
                        <p class="text-sm font-medium text-gray-800">{{ $user->name }}</p>
                        <p class="text-xs text-gray-500">{{ $user->email }}</p>
                    </div>
                </div>
            </td>
            <td class="px-5 py-3">
                @foreach($user->roles as $role)
                <span class="text-xs px-2 py-1 rounded-full font-medium
                    @if($role->name === 'SuperAdmin') bg-red-100 text-red-700
                    @elseif($role->name === 'Admin') bg-orange-100 text-orange-700
                    @else bg-blue-100 text-blue-700 @endif">{{ $role->name }}</span>
                @endforeach
            </td>
            <td class="px-5 py-3">
                <span class="text-xs px-2 py-1 rounded-full {{ $user->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                    {{ $user->is_active ? 'Active' : 'Inactive' }}
                </span>
                @if($user->deleted_at)<span class="text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded-full ml-1">Deleted</span>@endif
            </td>
            <td class="px-5 py-3 text-sm text-gray-500">{{ $user->last_login_at?->diffForHumans() ?? 'Never' }}</td>
            <td class="px-5 py-3 text-right">
                @unless($user->deleted_at)
                <a href="{{ route('admin.users.edit', $user) }}" class="text-indigo-600 hover:text-indigo-800 text-sm mr-3"><i class="fas fa-pencil"></i></a>
                @if($user->id !== auth()->id())
                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline" onsubmit="return confirm('Deactivate user?')">
                    @csrf @method('DELETE')
                    <button class="text-red-400 hover:text-red-600 text-sm"><i class="fas fa-ban"></i></button>
                </form>
                @endif
                @endunless
            </td>
        </tr>
        @endforeach
        </tbody>
    </table>
    <div class="p-5 border-t border-gray-100">{{ $users->links() }}</div>
</div>
@endsection
