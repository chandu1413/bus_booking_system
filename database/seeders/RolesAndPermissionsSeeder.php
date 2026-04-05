<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'manage_projects',
            'manage_tasks',
            'view_reports',
            'manage_users',
            'manage_roles',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm, 'guard_name' => 'web']);
        }

        $superAdmin = Role::firstOrCreate(['name' => 'SuperAdmin', 'guard_name' => 'web']);
        $superAdmin->syncPermissions(Permission::all());

        $admin = Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'web']);
        $admin->syncPermissions(['manage_projects', 'manage_tasks', 'view_reports', 'manage_users']);

        $member = Role::firstOrCreate(['name' => 'Member', 'guard_name' => 'web']);
        $member->syncPermissions(['manage_tasks']);

        $operator = Role::create(['name' => 'Operator', 'guard_name' => 'web']);
        $customer = Role::create(['name' => 'Customer', 'guard_name' => 'web']);
    }
}