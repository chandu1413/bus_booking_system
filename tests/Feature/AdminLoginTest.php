<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;


use Database\Seeders\RolesAndPermissionsSeeder;

class AdminLoginTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolesAndPermissionsSeeder::class);
    }

    public function test_admin_can_login_with_correct_credentials()
    {
        $admin = User::factory()->create();
        $admin->assignRole('Admin');

        $response = $this->post('/login', [
            'email' => $admin->email,
            'password' => 'password',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticatedAs($admin);
    }

    public function test_admin_cannot_login_with_wrong_password()
    {
        $admin = User::factory()->create();
        $admin->assignRole('Admin');

        $response = $this->from('/login')->post('/login', [
            'email' => $admin->email,
            'password' => 'wrong-password',
        ]);

        $response->assertRedirect('/login');
        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_inactive_admin_cannot_login()
    {
        $admin = User::factory()->create(['is_active' => false]);
        $admin->assignRole('Admin');

        $response = $this->from('/login')->post('/login', [
            'email' => $admin->email,
            'password' => 'password',
        ]);

        $response->assertRedirect('/login');
        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_non_admin_user_cannot_access_admin_dashboard()
    {
        $user = User::factory()->create();
        $user->assignRole('Member');

        $this->actingAs($user);
        $response = $this->get('/admin/dashboard');
        $response->assertStatus(403);
    }

    public function test_login_fails_with_missing_fields()
    {
        $response = $this->from('/login')->post('/login', [
            'email' => '',
            'password' => '',
        ]);

        $response->assertRedirect('/login');
        $response->assertSessionHasErrors(['email', 'password']);
        $this->assertGuest();
    }

    public function test_admin_can_login_with_remember_me()
    {
        $admin = User::factory()->create();
        $admin->assignRole('Admin');

        $response = $this->post('/login', [
            'email' => $admin->email,
            'password' => 'password',
            'remember' => 'on',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticatedAs($admin);
        $response->assertCookie(Auth::guard()->getRecallerName());
    }
}
