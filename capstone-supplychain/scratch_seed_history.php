<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\InventoryTransaction;
use App\Models\DemandHistory;
use App\Models\Product;
use App\Models\StorageLocation;
use App\Models\User;
use Carbon\Carbon;

$admin = User::first();
$loc = StorageLocation::first();
$products = Product::all();

if ($products->isEmpty() || !$loc || !$admin) {
    echo "Required models missing.\n";
    exit(1);
}

// Clear synthetic test transactions if any, then populate realistic past 6 months
for ($i = 5; $i >= 1; $i--) {
    $monthDate = Carbon::now()->subMonths($i);
    
    // Check if transactions already exist for this month
    $count = InventoryTransaction::whereYear('created_at', $monthDate->year)
        ->whereMonth('created_at', $monthDate->month)
        ->count();

    if ($count === 0) {
        // Calculate total monthly demand from DemandHistory for this month
        $monthlyDemand = DemandHistory::whereYear('date', $monthDate->year)
            ->whereMonth('date', $monthDate->month)
            ->sum('quantity_issued');

        if ($monthlyDemand == 0) {
            $monthlyDemand = (6 - $i) * 115 + rand(20, 50);
        }

        $inboundQty = (int)round($monthlyDemand * 1.35);
        $outboundQty = (int)$monthlyDemand;

        // Create Stock In transaction
        InventoryTransaction::create([
            'product_id' => $products->random()->id,
            'storage_location_id' => $loc->id,
            'user_id' => $admin->id,
            'type' => 'STOCK_IN',
            'quantity_change' => $inboundQty,
            'quantity_before' => 100,
            'quantity_after' => 100 + $inboundQty,
            'reference_type' => 'PURCHASE_ORDER',
            'notes' => 'Monthly replenishment stock receipt',
            'created_at' => (clone $monthDate)->startOfMonth()->addDays(2),
            'updated_at' => (clone $monthDate)->startOfMonth()->addDays(2),
        ]);

        // Create Stock Out transaction
        InventoryTransaction::create([
            'product_id' => $products->random()->id,
            'storage_location_id' => $loc->id,
            'user_id' => $admin->id,
            'type' => 'STOCK_OUT',
            'quantity_change' => -$outboundQty,
            'quantity_before' => 100 + $inboundQty,
            'quantity_after' => 100 + $inboundQty - $outboundQty,
            'reference_type' => 'DISPATCH_ISSUE',
            'notes' => 'Monthly operational dispatch issue',
            'created_at' => (clone $monthDate)->startOfMonth()->addDays(15),
            'updated_at' => (clone $monthDate)->startOfMonth()->addDays(15),
        ]);

        echo "Seeded month [{$monthDate->format('Y-m')}]: Inbound = {$inboundQty}, Outbound = {$outboundQty}\n";
    }
}

echo "Done seeding historical transaction records.\n";
