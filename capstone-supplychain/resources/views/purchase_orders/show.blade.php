@extends('layouts.app')

@section('title', 'Purchase Order #' . $purchaseOrder->po_number)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Purchase Order #{{ $purchaseOrder->po_number }}</h3>
        <p class="text-muted small mb-0">Supplier: {{ $purchaseOrder->supplier->company_name ?? '' }} • Created By: {{ $purchaseOrder->createdBy->name ?? '' }}</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('purchase-orders.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fa-solid fa-arrow-left me-1"></i> Back to PO List
        </a>

        @if($purchaseOrder->status == 'PENDING_APPROVAL' && (Auth::user()->isAdmin() || Auth::user()->isManagement()))
            <form action="{{ route('purchase-orders.approve', $purchaseOrder->id) }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-success btn-sm fw-semibold"><i class="fa-solid fa-check me-1"></i> Approve PO</button>
            </form>
        @endif

        @if(in_array($purchaseOrder->status, ['APPROVED', 'ORDERED', 'PARTIALLY_RECEIVED']) && (Auth::user()->isAdmin() || Auth::user()->isWarehouseStaff()))
            <button class="btn btn-primary btn-sm fw-semibold shadow-sm" data-bs-toggle="modal" data-bs-target="#receiveGoodsModal">
                <i class="fa-solid fa-box-archive me-1"></i> Receive Goods & Update IMS
            </button>
        @endif
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0"><i class="fa-solid fa-list-check text-primary me-2"></i> Purchase Order Line Items</h5>
                <span class="badge {{ $purchaseOrder->status == 'RECEIVED' ? 'bg-success' : 'bg-info' }}">{{ $purchaseOrder->status }}</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Product Item</th>
                                <th class="text-center">Ordered</th>
                                <th class="text-center">Received</th>
                                <th class="text-end">Unit Price</th>
                                <th class="text-end">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($purchaseOrder->items as $item)
                            <tr>
                                <td class="fw-semibold">{{ $item->product->name ?? 'Product' }} <div class="text-muted small">SKU: {{ $item->product->sku ?? '' }}</div></td>
                                <td class="text-center fw-bold">{{ $item->quantity_ordered }}</td>
                                <td class="text-center fw-bold {{ $item->quantity_received >= $item->quantity_ordered ? 'text-success' : 'text-warning' }}">
                                    {{ $item->quantity_received }}
                                </td>
                                <td class="text-end">₱{{ number_format($item->unit_price, 2) }}</td>
                                <td class="text-end fw-bold text-dark">₱{{ number_format($item->subtotal, 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="table-light">
                            <tr>
                                <td colspan="4" class="text-end fw-bold">TOTAL ORDER AMOUNT:</td>
                                <td class="text-end fw-bold fs-5 text-primary">₱{{ number_format($purchaseOrder->total_amount, 2) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <!-- Goods Receiving History -->
        <div class="card shadow-sm">
            <div class="card-header bg-white py-3">
                <h5 class="fw-bold mb-0"><i class="fa-solid fa-truck-ramp-box text-success me-2"></i> Goods Receiving History Logs</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Receiving # / Date</th>
                                <th>Received By</th>
                                <th>DR / Invoice #</th>
                                <th>Items & Placement Bins</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($purchaseOrder->receivingRecords as $rcv)
                            <tr>
                                <td class="fw-semibold">
                                    {{ $rcv->receiving_number }}
                                    <div class="text-muted small">{{ $rcv->created_at->format('M d, Y H:i') }}</div>
                                </td>
                                <td class="small">{{ $rcv->receivedBy->name ?? 'Staff' }}</td>
                                <td class="small">
                                    <div>DR: <code>{{ $rcv->delivery_receipt_number ?? 'N/A' }}</code></div>
                                    <div>Inv: <code>{{ $rcv->invoice_number ?? 'N/A' }}</code></div>
                                </td>
                                <td class="small">
                                    <ul class="list-unstyled mb-0">
                                        @foreach($rcv->items as $rItem)
                                            <li>• {{ $rItem->product->name ?? '' }}: <strong>+{{ $rItem->quantity_received }}</strong> units stored in bin <span class="badge bg-dark">{{ $rItem->storageLocation->code ?? 'N/A' }}</span></li>
                                        @endforeach
                                    </ul>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted">No goods receiving records created yet.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- DTRS Document Attachments Sidebar -->
    <div class="col-lg-4">
        <div class="card shadow-sm">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0"><i class="fa-solid fa-folder-closed text-primary me-2"></i> DTRS Documents</h5>
                <a href="{{ route('documents.index') }}" class="btn btn-sm btn-outline-primary"><i class="fa-solid fa-upload me-1"></i> Upload</a>
            </div>
            <div class="card-body p-3">
                @forelse($purchaseOrder->documentLinks as $link)
                    @if($link->document)
                    <div class="p-3 bg-light rounded border mb-2 d-flex align-items-center justify-content-between">
                        <div>
                            <div class="fw-bold small">{{ $link->document->title }}</div>
                            <div class="text-muted small" style="font-size:0.75rem;">{{ $link->document->document_number }} • {{ number_format($link->document->file_size_bytes / 1024, 1) }} KB</div>
                        </div>
                        <a href="{{ route('documents.download', $link->document->id) }}" class="btn btn-sm btn-primary">
                            <i class="fa-solid fa-download"></i>
                        </a>
                    </div>
                    @endif
                @empty
                    <div class="text-center py-3 text-muted small">No DTRS logistics documents linked to this PO yet.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Receive Goods Modal -->
<div class="modal fade" id="receiveGoodsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form action="{{ route('purchase-orders.receive', $purchaseOrder->id) }}" method="POST" class="modal-content">
            @csrf
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title fw-bold text-white"><i class="fa-solid fa-truck-ramp-box me-2"></i> Receive Order Goods & Stock-In</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Delivery Receipt (DR) Number</label>
                        <input type="text" name="delivery_receipt_number" class="form-control" placeholder="DR-908122" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Supplier Invoice Number</label>
                        <input type="text" name="invoice_number" class="form-control" placeholder="INV-55102" required>
                    </div>
                </div>

                <div class="card p-3 bg-light border mb-3">
                    <h6 class="fw-bold mb-3"><i class="fa-solid fa-box-open me-1"></i> Receive Items & Assign Storage Bins</h6>
                    @foreach($purchaseOrder->items as $idx => $item)
                        @if($item->quantity_received < $item->quantity_ordered)
                        <div class="row g-2 align-items-center mb-3 pb-2 border-bottom">
                            <div class="col-md-5">
                                <input type="hidden" name="items[{{ $idx }}][po_item_id]" value="{{ $item->id }}">
                                <div class="fw-bold">{{ $item->product->name ?? '' }}</div>
                                <div class="text-muted small">Ordered: {{ $item->quantity_ordered }} | Already Rcvd: {{ $item->quantity_received }}</div>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold small mb-1">Qty Received</label>
                                <input type="number" name="items[{{ $idx }}][quantity_received]" class="form-control form-control-sm" min="1" max="{{ $item->quantity_ordered - $item->quantity_received }}" value="{{ $item->quantity_ordered - $item->quantity_received }}" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold small mb-1">Assign Storage Bin</label>
                                <select name="items[{{ $idx }}][storage_location_id]" class="form-select form-select-sm" required>
                                    @foreach($locations as $loc)
                                        <option value="{{ $loc->id }}">{{ $loc->code }} ({{ $loc->zone }})</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        @endif
                    @endforeach
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary fw-semibold">Confirm Receiving & Update IMS</button>
            </div>
        </form>
    </div>
</div>
@endsection
