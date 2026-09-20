<?php

namespace App\Http\Controllers;

use App\Models\ProcurementRequest;
use App\Models\ProcurementRequestItem;
use App\Models\Product;
use App\Models\Supplier;
use App\Services\AuditLoggerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ProcurementController extends Controller
{
    public function index()
    {
        $requests = ProcurementRequest::with(['requestedBy', 'items.product'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $products = Product::where('is_active', true)->get();

        return view('procurement.index', compact('requests', 'products'));
    }

    public function create()
    {
        $products = Product::where('is_active', true)->get();
        return view('procurement.create', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'justification' => 'required|string|max:1000',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        $reqNumber = 'PR-' . date('Ymd') . '-' . strtoupper(Str::random(5));
        $totalCost = 0.0;

        $pr = ProcurementRequest::create([
            'request_number' => $reqNumber,
            'requested_by_user_id' => Auth::id(),
            'status' => 'SUBMITTED',
            'justification' => $request->justification,
            'total_estimated_cost' => 0.00,
        ]);

        foreach ($request->items as $itemData) {
            $product = Product::find($itemData['product_id']);
            $unitCost = $product ? $product->unit_cost : 0.0;
            $subtotal = $unitCost * $itemData['quantity'];
            $totalCost += $subtotal;

            ProcurementRequestItem::create([
                'procurement_request_id' => $pr->id,
                'product_id' => $itemData['product_id'],
                'quantity_requested' => $itemData['quantity'],
                'estimated_unit_cost' => $unitCost,
            ]);
        }

        $pr->total_estimated_cost = $totalCost;
        $pr->save();

        AuditLoggerService::log('CREATE_PROCUREMENT_REQUEST', 'Procurement', null, $pr->toArray());

        return redirect()->route('procurement.index')->with('success', "Procurement Request #{$reqNumber} submitted successfully!");
    }

    public function approve(ProcurementRequest $requestModel)
    {
        $requestModel->status = 'APPROVED';
        $requestModel->save();

        AuditLoggerService::log('APPROVE_PROCUREMENT_REQUEST', 'Procurement', null, $requestModel->toArray());

        return redirect()->route('procurement.index')->with('success', "Procurement Request #{$requestModel->request_number} has been APPROVED.");
    }
}
