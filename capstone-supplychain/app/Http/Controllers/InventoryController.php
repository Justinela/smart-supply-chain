<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\InventoryTransaction;
use App\Models\Product;
use App\Models\StorageLocation;
use App\Services\AiPredictionClient;
use App\Services\AuditLoggerService;
use App\Services\InventoryLedgerService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InventoryController extends Controller
{
    protected InventoryLedgerService $ledger;
    protected AiPredictionClient $aiClient;

    public function __construct(InventoryLedgerService $ledger, AiPredictionClient $aiClient)
    {
        $this->ledger = $ledger;
        $this->aiClient = $aiClient;
    }

    public function index()
    {
        $inventories = Inventory::with(['product.category', 'storageLocation.warehouse'])
            ->orderBy('updated_at', 'desc')
            ->paginate(15);

        $products = Product::where('is_active', true)->get();
        $locations = StorageLocation::with('warehouse')->where('is_active', true)->get();

        return view('inventory.index', compact('inventories', 'products', 'locations'));
    }

    public function stockIn(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'storage_location_id' => 'required|exists:storage_locations,id',
            'quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string|max:500',
        ]);

        try {
            $txn = $this->ledger->stockIn(
                $request->product_id,
                $request->storage_location_id,
                $request->quantity,
                Auth::id(),
                'MANUAL_STOCK_IN',
                null,
                $request->notes
            );

            AuditLoggerService::log('STOCK_IN', 'Inventory', null, $txn->toArray());

            return redirect()->route('inventory.index')->with('success', "Successfully stocked in {$request->quantity} unit(s).");
        } catch (Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

    public function stockOut(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'storage_location_id' => 'required|exists:storage_locations,id',
            'quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string|max:500',
        ]);

        try {
            $txn = $this->ledger->stockOut(
                $request->product_id,
                $request->storage_location_id,
                $request->quantity,
                Auth::id(),
                'MANUAL_STOCK_OUT',
                null,
                $request->notes
            );

            AuditLoggerService::log('STOCK_OUT', 'Inventory', null, $txn->toArray());

            return redirect()->route('inventory.index')->with('success', "Successfully stocked out {$request->quantity} unit(s).");
        } catch (Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

    public function transfer(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'source_location_id' => 'required|exists:storage_locations,id',
            'destination_location_id' => 'required|exists:storage_locations,id',
            'quantity' => 'required|integer|min:1',
            'ai_recommended' => 'nullable|boolean',
        ]);

        try {
            $trf = $this->ledger->transfer(
                $request->product_id,
                $request->source_location_id,
                $request->destination_location_id,
                $request->quantity,
                Auth::id(),
                $request->boolean('ai_recommended')
            );

            AuditLoggerService::log('TRANSFER', 'Inventory', null, $trf->toArray());

            return redirect()->route('inventory.index')->with('success', "Inventory Transfer #{$trf->transfer_number} completed successfully!");
        } catch (Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

    public function recommendLocation(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($request->product_id);
        $recommendation = $this->aiClient->getWarehouseRecommendation($product, $request->quantity);

        return response()->json($recommendation);
    }

    public function transactions()
    {
        $transactions = InventoryTransaction::with(['product', 'storageLocation.warehouse', 'user'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('inventory.transactions', compact('transactions'));
    }
}
