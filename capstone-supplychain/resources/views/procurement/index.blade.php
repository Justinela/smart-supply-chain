@extends('layouts.app')

@section('title', 'Procurement & Sourcing (PSM)')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Procurement & Sourcing Management (PSM)</h3>
        <p class="text-muted small mb-0">Create procurement requisitions, review sourcing options, and convert requests to Purchase Orders.</p>
    </div>
    <button class="btn btn-primary shadow-sm btn-sm fw-semibold" data-bs-toggle="modal" data-bs-target="#newRequestModal">
        <i class="fa-solid fa-plus me-1"></i> New Procurement Request
    </button>
</div>

<div class="card shadow-sm">
    <div class="card-header bg-white py-3">
        <h5 class="fw-bold mb-0"><i class="fa-solid fa-clipboard-list text-primary me-2"></i> Procurement Requisitions</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>PR Number</th>
                        <th>Requested By</th>
                        <th>Items Requested</th>
                        <th class="text-end">Total Est. Cost</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($requests as $req)
                    <tr>
                        <td class="fw-bold text-primary">{{ $req->request_number }}</td>
                        <td>
                            <div class="fw-semibold">{{ $req->requestedBy->name ?? 'User' }}</div>
                            <div class="text-muted small">{{ $req->created_at->format('M d, Y') }}</div>
                        </td>
                        <td>
                            <ul class="list-unstyled mb-0 small">
                                @foreach($req->items as $item)
                                    <li>• {{ $item->product->name ?? 'Product' }} (<strong>{{ $item->quantity_requested }}</strong> units)</li>
                                @endforeach
                            </ul>
                        </td>
                        <td class="text-end fw-bold text-dark">
                            ₱{{ number_format($req->total_estimated_cost, 2) }}
                        </td>
                        <td>
                            @if($req->status == 'APPROVED')
                                <span class="badge bg-success"><i class="fa-solid fa-check me-1"></i> APPROVED</span>
                            @elseif($req->status == 'CONVERTED_TO_PO')
                                <span class="badge bg-primary"><i class="fa-solid fa-file-invoice-dollar me-1"></i> PO GENERATED</span>
                            @else
                                <span class="badge bg-warning text-dark"><i class="fa-solid fa-clock me-1"></i> {{ $req->status }}</span>
                            @endif
                        </td>
                        <td>
                            @if($req->status == 'SUBMITTED' && (Auth::user()->isAdmin() || Auth::user()->isProcurementStaff() || Auth::user()->isManagement()))
                                <form action="{{ route('procurement.approve', $req->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-success">Approve Request</button>
                                </form>
                            @else
                                <span class="text-muted small">No actions pending</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">No procurement requests submitted yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer bg-white">
        {{ $requests->links() }}
    </div>
</div>

<!-- New Procurement Request Modal -->
<div class="modal fade" id="newRequestModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form action="{{ route('procurement.store') }}" method="POST" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title fw-bold"><i class="fa-solid fa-clipboard-check text-primary me-2"></i> Create Procurement Requisition</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Business Justification / Purpose</label>
                    <textarea name="justification" class="form-control" rows="2" placeholder="e.g., Replenishing stock for high-demand pressure sensors and aluminum bars." required></textarea>
                </div>

                <div class="card bg-light p-3 border mb-3">
                    <h6 class="fw-bold mb-3"><i class="fa-solid fa-cart-plus me-1"></i> Requested Line Items</h6>
                    <div class="row g-2 mb-2">
                        <div class="col-8">
                            <select name="items[0][product_id]" class="form-select" required>
                                @foreach($products as $p)
                                    <option value="{{ $p->id }}">{{ $p->name }} (Cost: ₱{{ number_format($p->unit_cost, 2) }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-4">
                            <input type="number" name="items[0][quantity]" class="form-control" placeholder="Qty" min="1" value="25" required>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary fw-semibold">Submit Requisition</button>
            </div>
        </form>
    </div>
</div>
@endsection
