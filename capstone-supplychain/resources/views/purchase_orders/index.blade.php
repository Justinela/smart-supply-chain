@extends('layouts.app')

@section('title', 'Purchase Order Management')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Purchase Order Management</h3>
        <p class="text-muted small mb-0">Create POs, track approval lifecycles, record partial/full receiving, and link DTRS documents.</p>
    </div>
    <button class="btn btn-primary shadow-sm btn-sm fw-semibold" data-bs-toggle="modal" data-bs-target="#newPOModal">
        <i class="fa-solid fa-plus me-1"></i> Generate Purchase Order
    </button>
</div>

<div class="card shadow-sm">
    <div class="card-header bg-white py-3">
        <h5 class="fw-bold mb-0"><i class="fa-solid fa-file-invoice-dollar text-primary me-2"></i> Purchase Orders Directory</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>PO Number</th>
                        <th>Supplier</th>
                        <th>Order / Delivery Date</th>
                        <th class="text-end">Total Amount</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($purchaseOrders as $po)
                    <tr>
                        <td class="fw-bold text-primary">{{ $po->po_number }}</td>
                        <td class="fw-semibold">{{ $po->supplier->company_name ?? 'Supplier' }}</td>
                        <td class="small">
                            <div>Order: {{ $po->order_date ? $po->order_date->format('M d, Y') : '-' }}</div>
                            <div class="text-muted">Delivery: {{ $po->expected_delivery_date ? $po->expected_delivery_date->format('M d, Y') : '-' }}</div>
                        </td>
                        <td class="text-end fw-bold text-dark">₱{{ number_format($po->total_amount, 2) }}</td>
                        <td>
                            @if($po->status == 'APPROVED')
                                <span class="badge bg-info text-dark"><i class="fa-solid fa-check me-1"></i> APPROVED</span>
                            @elseif($po->status == 'PARTIALLY_RECEIVED')
                                <span class="badge bg-warning text-dark"><i class="fa-solid fa-boxes-packing me-1"></i> PARTIAL</span>
                            @elseif($po->status == 'COMPLETED')
                                <span class="badge bg-success"><i class="fa-solid fa-circle-check me-1"></i> COMPLETED</span>
                            @else
                                <span class="badge bg-secondary"><i class="fa-solid fa-clock me-1"></i> DRAFT</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <a href="{{ route('purchase-orders.show', $po->id) }}" class="btn btn-outline-primary btn-sm me-1" title="View Details">
                                <i class="fa-solid fa-eye"></i>
                            </a>
                            @if($po->status == 'DRAFT')
                                <form action="{{ route('purchase-orders.approve', $po->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-success btn-sm" title="Approve PO">
                                        <i class="fa-solid fa-check"></i> Approve
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">No purchase orders found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer bg-white">
        {{ $purchaseOrders->links() }}
    </div>
</div>

<!-- Create Purchase Order Modal -->
<div class="modal fade" id="createPoModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form action="{{ route('purchase-orders.store') }}" method="POST" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title fw-bold"><i class="fa-solid fa-file-circle-plus me-2 text-primary"></i> Create Purchase Order</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Supplier Vendor</label>
                        <select name="supplier_id" class="form-select" required>
                            @foreach($suppliers as $s)
                                <option value="{{ $s->id }}">{{ $s->company_name }} ({{ $s->code }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Linked Procurement Requisition (Optional)</label>
                        <select name="procurement_request_id" class="form-select">
                            <option value="">-- None (Direct Purchase) --</option>
                            @foreach($procurementRequests as $pr)
                                <option value="{{ $pr->id }}">{{ $pr->request_number }} (Est: ₱{{ number_format($pr->total_estimated_cost, 2) }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Order Date</label>
                        <input type="date" name="order_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Expected Delivery Date</label>
                        <input type="date" name="expected_delivery_date" class="form-control" value="{{ date('Y-m-d', strtotime('+7 days')) }}" required>
                    </div>
                </div>

                <div class="card bg-light p-3 border mb-3">
                    <h6 class="fw-bold mb-2"><i class="fa-solid fa-list me-1"></i> Order Items</h6>
                    <div class="row g-2">
                        <div class="col-5">
                            <select name="items[0][product_id]" class="form-select" required>
                                @foreach($products as $p)
                                    <option value="{{ $p->id }}">{{ $p->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-3">
                            <input type="number" name="items[0][quantity]" class="form-control" placeholder="Qty" min="1" value="50" required>
                        </div>
                        <div class="col-4">
                            <input type="number" step="0.01" name="items[0][unit_price]" class="form-control" placeholder="Unit Price (₱)" value="140.00" required>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary fw-semibold">Create Purchase Order</button>
            </div>
        </form>
    </div>
</div>
@endsection
