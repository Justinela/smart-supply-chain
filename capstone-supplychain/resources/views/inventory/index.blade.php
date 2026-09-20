@extends('layouts.app')

@section('title', 'Inventory Management System (IMS)')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Inventory Management System (IMS)</h3>
        <p class="text-muted small mb-0">Track real-time stock balances across warehouse bins and execute stock transfers.</p>
    </div>
    <div class="d-flex gap-2">
        <button class="btn btn-success shadow-sm btn-sm fw-semibold" data-bs-toggle="modal" data-bs-target="#stockInModal">
            <i class="fa-solid fa-plus me-1"></i> Stock In
        </button>
        <button class="btn btn-danger shadow-sm btn-sm fw-semibold" data-bs-toggle="modal" data-bs-target="#stockOutModal">
            <i class="fa-solid fa-minus me-1"></i> Stock Out
        </button>
        <button class="btn btn-primary shadow-sm btn-sm fw-semibold" data-bs-toggle="modal" data-bs-target="#transferModal">
            <i class="fa-solid fa-right-left me-1"></i> Transfer Stock
        </button>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-header bg-white py-3">
        <h5 class="fw-bold mb-0 text-slate-800"><i class="fa-solid fa-cubes text-primary me-2"></i> Current Stock Balances by Storage Location</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Product Info</th>
                        <th>Category</th>
                        <th>Storage Location</th>
                        <th class="text-center">Qty On Hand</th>
                        <th class="text-center">Reserved</th>
                        <th class="text-center">Available</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($inventories as $inv)
                    <tr>
                        <td>
                            <div class="fw-semibold text-primary">{{ $inv->product->name ?? 'Product' }}</div>
                            <div class="text-muted small">SKU: {{ $inv->product->sku ?? '' }}</div>
                        </td>
                        <td>
                            <span class="badge bg-secondary-subtle text-dark border">{{ $inv->product->category->name ?? 'General' }}</span>
                        </td>
                        <td>
                            <div class="fw-semibold">{{ $inv->storageLocation->code ?? 'Unassigned' }}</div>
                            <div class="text-muted small">{{ $inv->storageLocation->warehouse->name ?? '' }} ({{ $inv->storageLocation->zone ?? '' }})</div>
                        </td>
                        <td class="text-center fw-bold fs-6">
                            {{ $inv->quantity_on_hand }} {{ $inv->product->unit_of_measure ?? 'pcs' }}
                        </td>
                        <td class="text-center text-muted">
                            {{ $inv->quantity_reserved }}
                        </td>
                        <td class="text-center fw-bold text-success">
                            {{ $inv->availableQuantity() }}
                        </td>
                        <td>
                            @if($inv->quantity_on_hand <= ($inv->product->reorder_point ?? 10))
                                <span class="badge bg-warning text-dark"><i class="fa-solid fa-triangle-exclamation me-1"></i> Reorder Point</span>
                            @else
                                <span class="badge bg-success-subtle text-success"><i class="fa-solid fa-check me-1"></i> In Stock</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">No inventory balances recorded yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer bg-white">
        {{ $inventories->links() }}
    </div>
</div>

<!-- Stock In Modal -->
<div class="modal fade" id="stockInModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('inventory.stockIn') }}" method="POST" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title fw-bold text-success"><i class="fa-solid fa-circle-plus me-2"></i> Stock In Goods</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Select Product</label>
                    <select name="product_id" class="form-select" required>
                        @foreach($products as $p)
                            <option value="{{ $p->id }}">{{ $p->name }} ({{ $p->sku }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Destination Storage Location</label>
                    <select name="storage_location_id" class="form-select" required>
                        @foreach($locations as $loc)
                            <option value="{{ $loc->id }}">{{ $loc->code }} — {{ $loc->warehouse->name ?? '' }} ({{ $loc->zone }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Quantity to Add</label>
                    <input type="number" name="quantity" class="form-control" min="1" required value="10">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Transaction Notes / Reference</label>
                    <textarea name="notes" class="form-control" rows="2" placeholder="e.g., Stock in from supplier shipment"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-success fw-semibold">Submit Stock In</button>
            </div>
        </form>
    </div>
</div>

<!-- Stock Out Modal -->
<div class="modal fade" id="stockOutModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('inventory.stockOut') }}" method="POST" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title fw-bold text-danger"><i class="fa-solid fa-circle-minus me-2"></i> Stock Out Goods</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Select Product</label>
                    <select name="product_id" class="form-select" required>
                        @foreach($products as $p)
                            <option value="{{ $p->id }}">{{ $p->name }} ({{ $p->sku }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Source Storage Location</label>
                    <select name="storage_location_id" class="form-select" required>
                        @foreach($locations as $loc)
                            <option value="{{ $loc->id }}">{{ $loc->code }} — {{ $loc->warehouse->name ?? '' }} ({{ $loc->zone }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Quantity to Issue</label>
                    <input type="number" name="quantity" class="form-control" min="1" required value="5">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Notes / Purpose</label>
                    <textarea name="notes" class="form-control" rows="2" placeholder="e.g., Dispatched for assembly order #9021"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-danger fw-semibold">Execute Stock Out</button>
            </div>
        </form>
    </div>
</div>

<!-- Stock Transfer Modal with Scikit-Learn Smart Location AI Assistant -->
<div class="modal fade" id="transferModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form action="{{ route('inventory.transfer') }}" method="POST" class="modal-content">
            @csrf
            <input type="hidden" name="ai_recommended" id="ai_recommended_input" value="0">

            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title fw-bold text-white d-flex align-items-center gap-2">
                    <i class="fa-solid fa-right-left text-primary"></i> Inventory Transfer & Smart Warehouse Placement
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Select Product</label>
                        <select name="product_id" id="trf_product_id" class="form-select" required>
                            @foreach($products as $p)
                                <option value="{{ $p->id }}">{{ $p->name }} ({{ $p->sku }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Transfer Quantity</label>
                        <input type="number" name="quantity" id="trf_quantity" class="form-control" min="1" value="20" required>
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Source Location</label>
                        <select name="source_location_id" class="form-select" required>
                            @foreach($locations as $loc)
                                <option value="{{ $loc->id }}">{{ $loc->code }} — {{ $loc->warehouse->name ?? '' }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <label class="form-label fw-semibold mb-0">Destination Location</label>
                            <button type="button" class="btn btn-xs btn-outline-purple border text-purple fw-semibold p-1" style="font-size:0.75rem;" onclick="fetchAiLocationRecommendation()">
                                <i class="fa-solid fa-wand-magic-sparkles me-1"></i> AI Recommend Bins
                            </button>
                        </div>
                        <select name="destination_location_id" id="trf_destination_location_id" class="form-select" required>
                            @foreach($locations as $loc)
                                <option value="{{ $loc->id }}">{{ $loc->code }} — {{ $loc->warehouse->name ?? '' }} ({{ $loc->zone }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- AI Recommendation Box -->
                <div id="ai_recommendation_box" class="p-3 rounded border mt-3 d-none" style="background: var(--bg-secondary); border-color: var(--border-color) !important; color: var(--text-primary);">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <div class="fw-bold text-info"><i class="fa-solid fa-brain me-2"></i> Scikit-Learn AI Recommendation Results:</div>
                        <span class="ai-badge">Confidence Score</span>
                    </div>
                    <div id="ai_results_container" class="small">
                        <!-- Dynamic AI response content injected here -->
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary fw-semibold">Confirm Location Transfer</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
function fetchAiLocationRecommendation() {
    const productId = document.getElementById('trf_product_id').value;
    const quantity = document.getElementById('trf_quantity').value;
    const box = document.getElementById('ai_recommendation_box');
    const container = document.getElementById('ai_results_container');

    box.classList.remove('d-none');
    container.innerHTML = `<div class="text-muted"><i class="fa-solid fa-spinner fa-spin me-2"></i> Contacting Scikit-Learn AI microservice for location evaluation...</div>`;

    fetch("{{ route('inventory.recommendLocation') }}", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ product_id: productId, quantity: quantity })
    })
    .then(res => res.json())
    .then(data => {
        if (data.recommendations && data.recommendations.length > 0) {
            let html = `<div class="list-group">`;
            data.recommendations.forEach((rec, idx) => {
                const isTop = idx === 0;
                html += `
                    <div class="list-group-item bg-dark text-white border-secondary mb-2 rounded p-2">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fw-bold text-warning"><i class="fa-solid fa-location-dot me-1"></i> ${rec.location_code} (${rec.zone})</span>
                            <span class="badge ${isTop ? 'bg-success' : 'bg-secondary'}">${Math.round(rec.suitability_score * 100)}% Match Score</span>
                        </div>
                        <ul class="text-slate-300 small mb-1 mt-1 ps-3">
                            ${rec.reasons.map(r => `<li>${r}</li>`).join('')}
                        </ul>
                        <button type="button" class="btn btn-sm btn-outline-info w-100 mt-1 py-0" style="font-size:0.75rem;" onclick="applyRecommendation(${rec.storage_location_id})">
                            <i class="fa-solid fa-check me-1"></i> Select Location ${rec.location_code}
                        </button>
                    </div>
                `;
            });
            html += `</div>`;
            container.innerHTML = html;
        } else {
            container.innerHTML = `<div class="text-warning">No optimal storage locations matching volume/weight constraints.</div>`;
        }
    })
    .catch(err => {
        container.innerHTML = `<div class="text-danger">Failed to connect to AI service. Defaulting to heuristic storage placement.</div>`;
    });
}

function applyRecommendation(locationId) {
    document.getElementById('trf_destination_location_id').value = locationId;
    document.getElementById('ai_recommended_input').value = "1";
    alert('AI Recommended storage location successfully applied!');
}
</script>
@endsection
