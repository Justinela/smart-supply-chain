<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserManagementFilterAndRbacTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    public function test_admin_can_access_user_management()
    {
        $admin = User::whereHas('role', fn($q)=>$q->where('name', 'admin'))->first();

        $response = $this->actingAs($admin)->get(route('admin.users.index'));
        $response->assertStatus(200);
        $response->assertSee('User Management');
    }

    public function test_non_admin_cannot_access_user_management()
    {
        $whUser = User::whereHas('role', fn($q)=>$q->where('name', 'warehouse_staff'))->first();

        $response = $this->actingAs($whUser)->get(route('admin.users.index'));
        $response->assertStatus(403);
    }

    public function test_role_search_and_status_filtering()
    {
        $admin = User::whereHas('role', fn($q)=>$q->where('name', 'admin'))->first();
        $whRole = Role::where('name', 'warehouse_staff')->first();
        $procRole = Role::where('name', 'procurement_staff')->first();

        // 1. Role filtering by Warehouse Staff
        $responseRole = $this->actingAs($admin)->get(route('admin.users.index', ['role_id' => $whRole->id]));
        $responseRole->assertStatus(200);
        $responseRole->assertSee('John Warehouse');
        $responseRole->assertDontSee('Sarah Procurement');

        // 2. Role filtering by Procurement Staff
        $responseProc = $this->actingAs($admin)->get(route('admin.users.index', ['role_id' => $procRole->id]));
        $responseProc->assertStatus(200);
        $responseProc->assertSee('Sarah Procurement');
        $responseProc->assertDontSee('John Warehouse');

        // 3. Status filtering
        $responseActive = $this->actingAs($admin)->get(route('admin.users.index', ['status' => 'active']));
        $responseActive->assertStatus(200);
        $responseActive->assertSee('John Warehouse');

        // 4. Combined search + role + status filtering
        $responseCombined = $this->actingAs($admin)->get(route('admin.users.index', [
            'search' => 'John',
            'role_id' => $whRole->id,
            'status' => 'active'
        ]));
        $responseCombined->assertStatus(200);
        $responseCombined->assertSee('John Warehouse');
        $responseCombined->assertDontSee('Sarah Procurement');
    }

    public function test_edit_user_saves_name_role_and_status_changes()
    {
        $admin = User::whereHas('role', fn($q)=>$q->where('name', 'admin'))->first();
        $targetUser = User::whereHas('role', fn($q)=>$q->where('name', 'warehouse_staff'))->first();

        // 1. Update status to Inactive (0)
        $responseInactive = $this->actingAs($admin)->put(route('admin.users.update', $targetUser->id), [
            'name' => 'John Warehouse Updated',
            'username' => 'warehouse01_updated',
            'email' => $targetUser->email,
            'role_id' => $targetUser->role_id,
            'is_active' => '0',
        ]);

        $responseInactive->assertRedirect(route('admin.users.index'));
        $targetUser->refresh();
        $this->assertEquals('John Warehouse Updated', $targetUser->name);
        $this->assertEquals('warehouse01_updated', $targetUser->username);
        $this->assertFalse($targetUser->is_active);

        // 2. Update status back to Active (1)
        $responseActive = $this->actingAs($admin)->put(route('admin.users.update', $targetUser->id), [
            'name' => 'John Warehouse Active',
            'username' => 'warehouse01_updated',
            'email' => $targetUser->email,
            'role_id' => $targetUser->role_id,
            'is_active' => '1',
        ]);

        $responseActive->assertRedirect(route('admin.users.index'));
        $targetUser->refresh();
        $this->assertTrue($targetUser->is_active);
    }
}
