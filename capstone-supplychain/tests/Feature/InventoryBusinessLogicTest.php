<?php

namespace Tests\Feature;

use App\Models\Inventory;
use App\Models\Product;
use App\Models\StorageLocation;
use App\Models\User;
use App\Models\Warehouse;
use App\Services\InventoryLedgerService;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InventoryBusinessLogicTest extends TestCase
{
    use RefreshDatabase;

    protected InventoryLedgerService $ledger;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
        $this->ledger = app(InventoryLedgerService::class);
    }

    public function test_stock_out_blocks_eating_into_reserved_stock()
    {
        $product = Product::first();
        $location = StorageLocation::where('is_active', true)->first();
        $user = User::first();

        // Set up inventory: on hand 10, reserved 8 -> unreserved = 2
        $inv = Inventory::updateOrCreate(
            ['product_id' => $product->id, 'storage_location_id' => $location->id],
            ['quantity_on_hand' => 10, 'quantity_reserved' => 8]
        );

        // Requesting stock out of 5 should fail because available unreserved is only 2
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Insufficient unreserved inventory');

        $this->ledger->stockOut($product->id, $location->id, 5, $user->id);
    }

    public function test_stock_in_blocks_capacity_overflow()
    {
        $product = Product::first();
        $user = User::first();

        // Create small storage location max volume 0.005 m3
        $wh = Warehouse::first();
        $location = StorageLocation::create([
            'warehouse_id' => $wh->id,
            'code' => 'TINY-LOC-01',
            'max_weight_kg' => 100,
            'max_volume_m3' => 0.0050,
            'occupied_volume_m3' => 0.0040,
            'is_active' => true,
        ]);

        // Attempting to stock in 100 units of product with volume 0.002 m3 = 0.2 m3 > 0.001 available
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('capacity exceeded');

        $this->ledger->stockIn($product->id, $location->id, 100, $user->id);
    }
}
