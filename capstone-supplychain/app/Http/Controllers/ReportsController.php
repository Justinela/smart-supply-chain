<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\DemandHistory;
use App\Models\Inventory;
use App\Models\Product;
use App\Models\PurchaseOrder;
use App\Models\Supplier;
use App\Models\Warehouse;
use App\Services\AiPredictionClient;
use Illuminate\Http\Request;

class ReportsController extends Controller
{
    protected AiPredictionClient $aiClient;

    public function __construct(AiPredictionClient $aiClient)
    {
        $this->aiClient = $aiClient;
    }

    public function index()
    {
        $products = Product::where('is_active', true)->get();
        $warehouses = Warehouse::with('storageLocations.inventories.product')->get();
        $suppliers = Supplier::withCount('purchaseOrders')->get();
        $recentLogs = AuditLog::with('user')->orderBy('created_at', 'desc')->take(10)->get();

        // Run Scikit-Learn evaluation report for featured product
        $sampleProduct = Product::first();
        $aiReport = null;
        if ($sampleProduct) {
            $aiReport = $this->aiClient->getDemandForecast($sampleProduct, 30);
        }

        return view('reports.index', compact('products', 'warehouses', 'suppliers', 'recentLogs', 'sampleProduct', 'aiReport'));
    }
}
