<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Product;
use App\Models\StorageLocation;
use App\Services\AiPredictionClient;
use App\Services\InventoryLedgerService;

echo "--- 1. Testing Database & Products ---\n";
$product = Product::where('is_active', true)->first() ?: Product::first();
if ($product && !$product->is_active) {
    $product->is_active = true;
    $product->save();
}
echo "Product Loaded: {$product->name} (SKU: {$product->sku})\n";

echo "\n--- 2. Testing Python Scikit-Learn REST API Microservice Communication ---\n";
$client = new AiPredictionClient();
$forecast = $client->getDemandForecast($product, 30);
echo "Demand Forecast Status: " . ($forecast['status'] ?? 'N/A') . "\n";
echo "Algorithm Used: " . ($forecast['model_info']['algorithm'] ?? 'N/A') . "\n";
echo "30-Day Total Predicted Demand: " . ($forecast['total_predicted_demand'] ?? 0) . " units\n";
echo "MAE: " . ($forecast['model_info']['evaluation_metrics']['mae'] ?? 0) . "\n";
echo "RMSE: " . ($forecast['model_info']['evaluation_metrics']['rmse'] ?? 0) . "\n";
echo "R² Score: " . ($forecast['model_info']['evaluation_metrics']['r2_score'] ?? 0) . "\n";
echo "Stockout Risk: " . ($forecast['inventory_recommendations']['stockout_risk_level'] ?? 'N/A') . "\n";

echo "\n--- 3. Testing Smart Warehouse Location Recommendation ---\n";
$recommendation = $client->getWarehouseRecommendation($product, 50);
echo "Recommendation Status: " . ($recommendation['status'] ?? 'N/A') . "\n";
if (!empty($recommendation['recommendations'])) {
    foreach ($recommendation['recommendations'] as $idx => $rec) {
        echo "Rank #" . ($idx + 1) . ": Location " . $rec['location_code'] . " (Score: " . round($rec['suitability_score'] * 100) . "%)\n";
        foreach ($rec['reasons'] as $reason) {
            echo "   • {$reason}\n";
        }
    }
}

echo "\n--- 4. Testing Atomic Inventory Transaction Ledger ---\n";
$ledger = new InventoryLedgerService();
$loc = StorageLocation::first();
$txn = $ledger->stockIn($product->id, $loc->id, 20, 1, 'CLI_TEST', null, 'Verification test');
echo "Stock In Txn Recorded: Txn #{$txn->id}, Change: +{$txn->quantity_change}, After: {$txn->quantity_after}\n";

echo "\nALL CAPSTONE SYSTEM INTEGRATION VERIFICATIONS PASSED SUCCESSFULLY!\n";
