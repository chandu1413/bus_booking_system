<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function __construct()
    {
        // Only SuperAdmin and Admin
    }

    private function checkAccess(): void
    {
        if (!auth()->user()->isSuperAdmin() && !auth()->user()->isAdmin()) abort(403);
    }

    public function index()
    {
        $this->checkAccess();
        $users = User::with('roles')
            ->withTrashed()
            ->latest()
            ->paginate(20);
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        $this->checkAccess();
        $roles = Role::all();
        return view('admin.users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $this->checkAccess();
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'roles'    => 'required|array',
            'roles.*'  => 'exists:roles,id',
            'is_active'=> 'boolean',
        ]);
        $user = User::create([
            'name'      => $validated['name'],
            'email'     => $validated['email'],
            'password'  => Hash::make($validated['password']),
            'is_active' => $request->boolean('is_active', true),
        ]);
        $roles = Role::whereIn('id', $validated['roles'])->pluck('name');
        $user->syncRoles($roles);
        return redirect()->route('admin.users.index')->with('success', 'User created successfully!');
    }

    public function edit(User $user)
    {
        $this->checkAccess();
        $roles = Role::all();
        $userRoles = $user->roles->pluck('id')->toArray();
        return view('admin.users.edit', compact('user', 'roles', 'userRoles'));
    }

    public function update(Request $request, User $user)
    {
        $this->checkAccess();
        $validated = $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'required|email|unique:users,email,' . $user->id,
            'password'  => 'nullable|min:8|confirmed',
            'roles'     => 'required|array',
            'roles.*'   => 'exists:roles,id',
            'is_active' => 'boolean',
        ]);
        $user->update([
            'name'      => $validated['name'],
            'email'     => $validated['email'],
            'is_active' => $request->boolean('is_active', true),
        ]);
        if ($request->filled('password')) {
            $user->update(['password' => Hash::make($request->password)]);
        }
        $roles = Role::whereIn('id', $validated['roles'])->pluck('name');
        $user->syncRoles($roles);
        return redirect()->route('admin.users.index')->with('success', 'User updated!');
    }

    public function destroy(User $user)
    {
        $this->checkAccess();
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete yourself.');
        }
        $user->delete();
        return back()->with('success', 'User deactivated.');
    }
}