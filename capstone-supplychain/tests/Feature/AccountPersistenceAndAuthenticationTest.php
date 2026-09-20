<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AccountPersistenceAndAuthenticationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_executive_manager_account_persistence()
    {
        $execRole = Role::where('name', 'management')->first();

        // 1. Create Executive Manager Account
        $response = $this->withSession([
            'captcha_code' => 'TESTCODE',
            'captcha_expires_at' => now()->addMinutes(5)->timestamp,
        ])->post('/register', [
            'name' => 'Executive Manager Test',
            'username' => 'executive_test01',
            'email' => 'executive01@supplychain.test',
            'role_id' => $execRole->id,
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'captcha' => 'TESTCODE',
        ]);

        // 2. Assert Account Persists in Database
        $this->assertDatabaseHas('users', [
            'username' => 'executive_test01',
            'email' => 'executive01@supplychain.test',
            'role_id' => $execRole->id,
            'is_active' => 1,
        ]);

        $user = User::where('username', 'executive_test01')->first();
        $this->assertNotNull($user);
        $this->assertTrue($user->isManagement());
    }

    public function test_login_with_username_and_valid_captcha()
    {
        $user = User::where('email', 'management@supplychain.test')->first();
        $user->username = 'executive01';
        $user->save();

        session([
            'captcha_code' => 'VALID1',
            'captcha_expires_at' => now()->addMinutes(5)->timestamp,
        ]);

        $response = $this->post('/login', [
            'login' => 'executive01',
            'password' => 'password123',
            'captcha' => 'VALID1',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticatedAs($user);
    }

    public function test_login_blocked_with_invalid_captcha()
    {
        $user = User::where('email', 'management@supplychain.test')->first();
        $user->username = 'executive01';
        $user->save();

        session([
            'captcha_code' => 'SECRET',
            'captcha_expires_at' => now()->addMinutes(5)->timestamp,
        ]);

        $response = $this->post('/login', [
            'login' => 'executive01',
            'password' => 'password123',
            'captcha' => 'WRONGCODE',
        ]);

        $response->assertSessionHasErrors('captcha');
        $this->assertGuest();
    }

    public function test_logout_preserves_database_account()
    {
        $user = User::where('email', 'management@supplychain.test')->first();
        $user->username = 'executive01';
        $user->save();

        $this->actingAs($user);
        $response = $this->post('/logout');

        $response->assertRedirect('/login');
        $this->assertGuest();

        // Database record MUST still exist unchanged
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'email' => 'management@supplychain.test',
            'username' => 'executive01',
        ]);
    }

    public function test_login_again_after_logout()
    {
        $user = User::where('email', 'management@supplychain.test')->first();
        $user->username = 'executive01';
        $user->save();

        // 1. Log out
        $this->actingAs($user);
        $this->post('/logout');
        $this->assertGuest();

        // 2. Log in again using username
        session([
            'captcha_code' => 'REAUTH',
            'captcha_expires_at' => now()->addMinutes(5)->timestamp,
        ]);

        $response = $this->post('/login', [
            'login' => 'executive01',
            'password' => 'password123',
            'captcha' => 'REAUTH',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticatedAs($user);
    }

    public function test_user_search_by_username()
    {
        $admin = User::where('email', 'admin@supplychain.test')->first();

        $execUser = User::where('email', 'management@supplychain.test')->first();
        $execUser->username = 'executive01';
        $execUser->save();

        $response = $this->actingAs($admin)->get('/admin/users?search=executive01');

        $response->assertStatus(200);
        $response->assertSee('executive01');
    }

    public function test_other_roles_functionality()
    {
        $admin = User::where('email', 'admin@supplychain.test')->first();
        $warehouse = User::where('email', 'warehouse@supplychain.test')->first();
        $procurement = User::where('email', 'procurement@supplychain.test')->first();

        $this->assertTrue($admin->isAdmin());
        $this->assertTrue($warehouse->isWarehouseStaff());
        $this->assertTrue($procurement->isProcurementStaff());
    }
}
