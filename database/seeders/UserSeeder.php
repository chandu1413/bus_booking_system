<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $superAdmin = User::firstOrCreate(
            ['email' => 'superadmin@projectflow.com'],
            [
                'name'              => 'Super Admin',
                'password'          => Hash::make('password'),
                'is_active'         => true,
                'email_verified_at' => now(),
            ]
        );
        $superAdmin->syncRoles(['SuperAdmin']);

        $admin = User::firstOrCreate(
            ['email' => 'admin@projectflow.com'],
            [
                'name'              => 'Admin User',
                'password'          => Hash::make('password'),
                'is_active'         => true,
                'email_verified_at' => now(),
            ]
        );
        $admin->syncRoles(['Admin']);

        $members = [
            ['name' => 'Alice Johnson',  'email' => 'alice@projectflow.com'],
            ['name' => 'Bob Smith',      'email' => 'bob@projectflow.com'],
            ['name' => 'Carol Williams', 'email' => 'carol@projectflow.com'],
            ['name' => 'David Brown',    'email' => 'david@projectflow.com'],
            ['name' => 'Emma Davis',     'email' => 'emma@projectflow.com'],
        ];

        foreach ($members as $m) {
            $user = User::firstOrCreate(
                ['email' => $m['email']],
                [
                    'name'              => $m['name'],
                    'password'          => Hash::make('password'),
                    'is_active'         => true,
                    'email_verified_at' => now(),
                ]
            );
            $user->syncRoles(['Member']);
        }
    }
}