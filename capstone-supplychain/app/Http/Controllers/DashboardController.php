<?php

namespace App\Http\Controllers;

use App\Models\AiPrediction;
use App\Models\Inventory;
use App\Models\InventoryTransaction;
use App\Models\ProcurementRequest;
use App\Models\Product;
use App\Models\PurchaseOrder;
use App\Models\Supplier;
use App\Models\Warehouse;
use App\Services\AiPredictionClient;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    protected AiPredictionClient $aiClient;

    public function __construct(AiPredictionClient $aiClient)
    {
        $this->aiClient = $aiClient;
    }

    public function index()
    {
        $totalProducts = Product::where('is_active', true)->count();
        $totalWarehouses = Warehouse::where('is_active', true)->count();
        $pendingPRs = ProcurementRequest::where('status', 'PENDING_APPROVAL')->count();
        $pendingPOs = PurchaseOrder::whereIn('status', ['PENDING_APPROVAL', 'ORDERED'])->count();
        $activeSuppliers = Supplier::where('is_active', true)->count();

        $totalInventoryQty = Inventory::sum('quantity_on_hand');

        // Low stock products
        $products = Product::with('inventories')->where('is_active', true)->get();
        $lowStockProducts = $products->filter(function ($p) {
            return $p->totalQuantityOnHand() <= $p->reorder_point;
        });

        // Recent transactions
        $recentTransactions = InventoryTransaction::with(['product', 'storageLocation', 'user'])
            ->orderBy('created_at', 'desc')
            ->take(6)
            ->get();

        // Warehouses occupancy
        $warehouses = Warehouse::with('storageLocations')->where('is_active', true)->get();
        $totalCap = $warehouses->sum('total_capacity_m3');
        $totalOcc = $warehouses->sum('current_occupancy_m3');
        $overallUtilizationPct = $totalCap > 0 ? round(($totalOcc / $totalCap) * 100, 1) : 0;

        // AI Forecast Insights - query sample product for AI prediction display
        $featuredProduct = Product::where('is_active', true)->first();
        $aiForecast = null;
        if ($featuredProduct) {
            $aiForecast = $this->aiClient->getDemandForecast($featuredProduct, 30);
        }

        // 1. Monthly Stock Movements (Last 6 Months from live database transactions)
        $monthlyMonths = [];
        $monthlyInboundData = [];
        $monthlyOutboundData = [];

        for ($i = 5; $i >= 0; $i--) {
            $monthDate = now()->subMonths($i);
            $monthKey = $monthDate->format('Y-m');
            $monthlyMonths[] = $monthKey;

            $inboundSum = InventoryTransaction::whereYear('created_at', $monthDate->year)
                ->whereMonth('created_at', $monthDate->month)
                ->where('type', 'STOCK_IN')
                ->sum('quantity_change');

            $outboundSum = InventoryTransaction::whereYear('created_at', $monthDate->year)
                ->whereMonth('created_at', $monthDate->month)
                ->where('type', 'STOCK_OUT')
                ->sum('quantity_change');

            $monthlyInboundData[] = (int)$inboundSum;
            $monthlyOutboundData[] = (int)abs($outboundSum);
        }

        // 2. Category Stock Distribution (Live inventory counts per category)
        $categories = \App\Models\Category::with('products.inventories')->get();
        $categoryLabels = [];
        $categoryData = [];

        foreach ($categories as $cat) {
            $catQty = 0;
            foreach ($cat->products as $p) {
                $catQty += $p->totalQuantityOnHand();
            }
            if ($catQty > 0) {
                $categoryLabels[] = $cat->name;
                $categoryData[] = (int)$catQty;
            }
        }

        return view('dashboard.index', compact(
            'totalProducts',
            'totalWarehouses',
            'pendingPRs',
            'pendingPOs',
            'activeSuppliers',
            'totalInventoryQty',
            'overallUtilizationPct',
            'lowStockProducts',
            'recentTransactions',
            'warehouses',
            'featuredProduct',
            'aiForecast',
            'monthlyMonths',
            'monthlyInboundData',
            'monthlyOutboundData',
            'categoryLabels',
            'categoryData'
        ));
    }
}
