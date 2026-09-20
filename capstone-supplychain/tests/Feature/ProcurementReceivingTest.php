<?php

namespace Tests\Feature;

use App\Models\ProcurementRequest;
use App\Models\Product;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\Role;
use App\Models\StorageLocation;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProcurementReceivingTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_self_approval_prevention_on_purchase_order()
    {
        $role = Role::where('name', 'procurement_staff')->first();
        $user = User::create([
            'role_id' => $role->id,
            'name' => 'Officer Bob',
            'email' => 'bob@supplychain.test',
            'password' => bcrypt('password123'),
            'is_active' => true,
        ]);

        $supplier = Supplier::first();
        $po = PurchaseOrder::create([
            'po_number' => 'PO-TEST-001',
            'supplier_id' => $supplier->id,
            'created_by_user_id' => $user->id,
            'status' => 'PENDING_APPROVAL',
            'total_amount' => 100.00,
            'order_date' => now(),
            'expected_delivery_date' => now()->addDays(5),
        ]);

        // Attempting to approve own PO as non-admin procurement officer should fail
        $response = $this->actingAs($user)->post("/purchase-orders/{$po->id}/approve");
        $response->assertSessionHasErrors(['error']);
    }

    public function test_over_receiving_is_blocked()
    {
        $admin = User::where('email', 'admin@supplychain.test')->first();
        $product = Product::first();
        $supplier = Supplier::first();
        $location = StorageLocation::first();

        $po = PurchaseOrder::create([
            'po_number' => 'PO-TEST-002',
            'supplier_id' => $supplier->id,
            'created_by_user_id' => $admin->id,
            'status' => 'APPROVED',
            'total_amount' => 500.00,
            'order_date' => now(),
            'expected_delivery_date' => now()->addDays(5),
        ]);

        $poItem = PurchaseOrderItem::create([
            'purchase_order_id' => $po->id,
            'product_id' => $product->id,
            'quantity_ordered' => 10,
            'quantity_received' => 0,
            'unit_price' => 50.00,
            'subtotal' => 500.00,
        ]);

        // Attempt to receive 15 when ordered quantity is only 10
        $response = $this->actingAs($admin)->post("/purchase-orders/{$po->id}/receive", [
            'delivery_receipt_number' => 'DR-999',
            'items' => [
                [
                    'po_item_id' => $poItem->id,
                    'quantity_received' => 15,
                    'storage_location_id' => $location->id,
                ]
            ]
        ]);

        $response->assertSessionHasErrors(['error']);
    }
}
