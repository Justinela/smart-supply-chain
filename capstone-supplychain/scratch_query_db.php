<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== REAL MONTHLY MOVEMENTS CALCULATION ===\n";
$monthlyMonths = [];
$monthlyInboundData = [];
$monthlyOutboundData = [];

for ($i = 5; $i >= 0; $i--) {
    $monthDate = now()->subMonths($i);
    $monthKey = $monthDate->format('Y-m');
    $monthlyMonths[] = $monthKey;

    $inboundSum = App\Models\InventoryTransaction::whereYear('created_at', $monthDate->year)
        ->whereMonth('created_at', $monthDate->month)
        ->where('type', 'STOCK_IN')
        ->sum('quantity_change');

    $outboundSum = App\Models\InventoryTransaction::whereYear('created_at', $monthDate->year)
        ->whereMonth('created_at', $monthDate->month)
        ->where('type', 'STOCK_OUT')
        ->sum('quantity_change');

    $monthlyInboundData[] = (int)$inboundSum;
    $monthlyOutboundData[] = (int)abs($outboundSum);

    echo "Month [{$monthKey}]: Inbound = {$inboundSum} | Outbound = " . abs($outboundSum) . "\n";
}

echo "\n=== REAL CATEGORY STOCK DISTRIBUTION ===\n";
$categories = App\Models\Category::with('products.inventories')->get();
$categoryLabels = [];
$categoryData = [];

foreach ($categories as $cat) {
    $catQty = 0;
    foreach ($cat->products as $p) {
        $catQty += $p->totalQuantityOnHand();
    }
    if ($catQty > 0) {
        $categoryLabels[] = $cat->name;
        $categoryData[] = $catQty;
        echo "Category [{$cat->name}]: {$catQty} units\n";
    }
}
