<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    private function checkAccess(): void
    {
        if (!auth()->user()->isSuperAdmin()) {
            abort(403);
        }
    }

    public function index()
    {
        $this->checkAccess();
        $roles = Role::withCount('users')->with('permissions')->get();
        return view('admin.roles.index', compact('roles'));
    }

    public function create()
    {
        $this->checkAccess();
        $permissions = Permission::all()->groupBy(fn($p) => explode('_', $p->name)[0]);
        return view('admin.roles.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        $this->checkAccess();
        $request->validate([
            'name'          => 'required|string|max:255|unique:roles,name',
            'permissions'   => 'array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $role = Role::create(['name' => $request->name, 'guard_name' => 'web']);

        if ($request->permissions) {
            $perms = Permission::whereIn('id', $request->permissions)->pluck('name');
            $role->syncPermissions($perms);
        }

        return redirect()->route('admin.roles.index')
            ->with('success', 'Role created successfully!');
    }

    public function edit(Role $role)
    {
        $this->checkAccess();
        $permissions     = Permission::all()->groupBy(fn($p) => explode('_', $p->name)[0]);
        $rolePermissions = $role->permissions->pluck('id')->toArray();
        return view('admin.roles.edit', compact('role', 'permissions', 'rolePermissions'));
    }

    public function update(Request $request, Role $role)
    {
        $this->checkAccess();
        $request->validate([
            'name'          => 'required|string|max:255|unique:roles,name,' . $role->id,
            'permissions'   => 'array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $role->update(['name' => $request->name]);

        $perms = $request->permissions
            ? Permission::whereIn('id', $request->permissions)->pluck('name')
            : [];
        $role->syncPermissions($perms);

        return redirect()->route('admin.roles.index')
            ->with('success', 'Role updated successfully!');
    }

    public function destroy(Role $role)
    {
        $this->checkAccess();
        if (in_array($role->name, ['SuperAdmin', 'Admin', 'Member'])) {
            return back()->with('error', 'System roles cannot be deleted.');
        }
        $role->delete();
        return back()->with('success', 'Role deleted.');
    }
}