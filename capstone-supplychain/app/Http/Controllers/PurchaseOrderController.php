<?php

namespace App\Http\Controllers;

use App\Models\ProcurementRequest;
use App\Models\Product;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\ReceivingItem;
use App\Models\ReceivingRecord;
use App\Models\StorageLocation;
use App\Models\Supplier;
use App\Services\AuditLoggerService;
use App\Services\InventoryLedgerService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PurchaseOrderController extends Controller
{
    protected InventoryLedgerService $ledger;

    public function __construct(InventoryLedgerService $ledger)
    {
        $this->ledger = $ledger;
    }

    public function index()
    {
        $purchaseOrders = PurchaseOrder::with(['supplier', 'createdBy', 'approvedBy', 'items.product'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $suppliers = Supplier::where('is_active', true)->get();
        $procurementRequests = ProcurementRequest::where('status', 'APPROVED')->get();
        $products = Product::where('is_active', true)->get();

        return view('purchase_orders.index', compact('purchaseOrders', 'suppliers', 'procurementRequests', 'products'));
    }

    public function show(PurchaseOrder $purchaseOrder)
    {
        $purchaseOrder->load(['supplier', 'createdBy', 'approvedBy', 'items.product', 'receivingRecords.items.storageLocation', 'documentLinks.document']);
        $locations = StorageLocation::with('warehouse')->where('is_active', true)->get();

        return view('purchase_orders.show', compact('purchaseOrder', 'locations'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'procurement_request_id' => 'nullable|exists:procurement_requests,id',
            'order_date' => 'required|date',
            'expected_delivery_date' => 'required|date|after_or_equal:order_date',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0.01',
        ]);

        $poNumber = 'PO-' . date('Ymd') . '-' . strtoupper(Str::random(5));
        $totalAmount = 0.0;

        $po = PurchaseOrder::create([
            'po_number' => $poNumber,
            'procurement_request_id' => $request->procurement_request_id,
            'supplier_id' => $request->supplier_id,
            'created_by_user_id' => Auth::id(),
            'status' => 'PENDING_APPROVAL',
            'total_amount' => 0.00,
            'order_date' => $request->order_date,
            'expected_delivery_date' => $request->expected_delivery_date,
            'notes' => $request->notes,
        ]);

        foreach ($request->items as $itemData) {
            $subtotal = $itemData['quantity'] * $itemData['unit_price'];
            $totalAmount += $subtotal;

            PurchaseOrderItem::create([
                'purchase_order_id' => $po->id,
                'product_id' => $itemData['product_id'],
                'quantity_ordered' => $itemData['quantity'],
                'quantity_received' => 0,
                'unit_price' => $itemData['unit_price'],
                'subtotal' => $subtotal,
            ]);
        }

        $po->total_amount = $totalAmount;
        $po->save();

        if ($request->procurement_request_id) {
            $pr = ProcurementRequest::find($request->procurement_request_id);
            if ($pr) {
                $pr->status = 'CONVERTED_TO_PO';
                $pr->save();
            }
        }

        AuditLoggerService::log('CREATE_PURCHASE_ORDER', 'PurchaseOrder', null, $po->toArray());

        return redirect()->route('purchase-orders.index')->with('success', "Purchase Order #{$poNumber} created and submitted for approval!");
    }

    public function approve(PurchaseOrder $purchaseOrder)
    {
        if ($purchaseOrder->status !== 'PENDING_APPROVAL') {
            return back()->withErrors(['error' => "Purchase Order #{$purchaseOrder->po_number} is not pending approval."]);
        }

        $user = Auth::user();
        if ($purchaseOrder->created_by_user_id === $user->id && !$user->isAdmin()) {
            return back()->withErrors(['error' => "Segregation of duties policy: You cannot approve a Purchase Order that you created."]);
        }

        $purchaseOrder->status = 'APPROVED';
        $purchaseOrder->approved_by_user_id = $user->id;
        $purchaseOrder->save();

        AuditLoggerService::log('APPROVE_PURCHASE_ORDER', 'PurchaseOrder', null, $purchaseOrder->toArray());

        return redirect()->route('purchase-orders.show', $purchaseOrder->id)->with('success', "Purchase Order #{$purchaseOrder->po_number} APPROVED!");
    }

    public function receive(Request $request, PurchaseOrder $purchaseOrder)
    {
        if (!in_array($purchaseOrder->status, ['APPROVED', 'PARTIALLY_RECEIVED'])) {
            return back()->withErrors(['error' => "Receiving failed: Purchase Order status '{$purchaseOrder->status}' does not permit receiving goods."]);
        }

        $request->validate([
            'delivery_receipt_number' => 'nullable|string|max:100',
            'invoice_number' => 'nullable|string|max:100',
            'items' => 'required|array|min:1',
            'items.*.po_item_id' => 'required|exists:purchase_order_items,id',
            'items.*.quantity_received' => 'required|integer|min:1',
            'items.*.storage_location_id' => 'required|exists:storage_locations,id',
        ]);

        try {
            DB::transaction(function () use ($request, $purchaseOrder) {
                $rcvNum = 'RCV-' . date('Ymd') . '-' . strtoupper(Str::random(5));

                $rcvRecord = ReceivingRecord::create([
                    'receiving_number' => $rcvNum,
                    'purchase_order_id' => $purchaseOrder->id,
                    'received_by_user_id' => Auth::id(),
                    'delivery_receipt_number' => $request->delivery_receipt_number,
                    'invoice_number' => $request->invoice_number,
                    'status' => 'COMPLETED',
                    'notes' => $request->notes,
                ]);

                $allReceived = true;

                foreach ($request->items as $itemData) {
                    $poItem = PurchaseOrderItem::with('product')->findOrFail($itemData['po_item_id']);
                    
                    // IDOR Check
                    if ($poItem->purchase_order_id !== $purchaseOrder->id) {
                        throw new Exception("Security Error: Line item #{$poItem->id} does not belong to Purchase Order #{$purchaseOrder->po_number}.");
                    }

                    $qtyRcv = intval($itemData['quantity_received']);
                    $remainingQty = $poItem->quantity_ordered - $poItem->quantity_received;

                    // Over-receiving Check
                    if ($qtyRcv > $remainingQty) {
                        $pName = $poItem->product->name ?? 'Product';
                        throw new Exception("Receiving error for '{$pName}': Submitted quantity ({$qtyRcv}) exceeds remaining ordered quantity ({$remainingQty}).");
                    }

                    ReceivingItem::create([
                        'receiving_record_id' => $rcvRecord->id,
                        'purchase_order_item_id' => $poItem->id,
                        'product_id' => $poItem->product_id,
                        'quantity_received' => $qtyRcv,
                        'quantity_accepted' => $qtyRcv,
                        'quantity_rejected' => 0,
                        'storage_location_id' => $itemData['storage_location_id'],
                    ]);

                    // Update PO Item quantity_received
                    $poItem->quantity_received += $qtyRcv;
                    $poItem->save();

                    // Re-check all items in PO for status calculation
                    $poItemCount = PurchaseOrderItem::where('purchase_order_id', $purchaseOrder->id)
                        ->whereRaw('quantity_received < quantity_ordered')
                        ->count();

                    if ($poItemCount > 0) {
                        $allReceived = false;
                    }

                    // Stock-In to Inventory Ledger (triggers active location and capacity validation)
                    $this->ledger->stockIn(
                        $poItem->product_id,
                        $itemData['storage_location_id'],
                        $qtyRcv,
                        Auth::id(),
                        'PO_RECEIVING',
                        $rcvRecord->id,
                        "Received against PO #{$purchaseOrder->po_number}"
                    );
                }

                $purchaseOrder->status = $allReceived ? 'RECEIVED' : 'PARTIALLY_RECEIVED';
                $purchaseOrder->save();

                AuditLoggerService::log('RECEIVE_PO_GOODS', 'PurchaseOrder', null, $rcvRecord->toArray());
            });

            return redirect()->route('purchase-orders.show', $purchaseOrder->id)->with('success', "Goods successfully received and stock balance updated!");
        } catch (Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }
}
