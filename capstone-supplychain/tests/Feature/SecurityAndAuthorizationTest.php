<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SecurityAndAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_guest_is_redirected_to_login()
    {
        $response = $this->get('/dashboard');
        $response->assertRedirect('/login');
    }

    public function test_non_admin_cannot_access_user_management()
    {
        $user = User::where('email', 'warehouse@supplychain.test')->first();
        $response = $this->actingAs($user)->get('/admin/users');
        $response->assertStatus(403);
    }

    public function test_admin_can_access_user_management()
    {
        $admin = User::where('email', 'admin@supplychain.test')->first();
        $response = $this->actingAs($admin)->get('/admin/users');
        $response->assertStatus(200);
    }
}
