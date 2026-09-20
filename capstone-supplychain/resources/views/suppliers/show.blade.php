@extends('layouts.app')

@section('title', 'Supplier Catalog - ' . $supplier->company_name)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">{{ $supplier->company_name }}</h3>
        <p class="text-muted small mb-0">Code: {{ $supplier->code }} • Contact: {{ $supplier->contact_person }} ({{ $supplier->email }})</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('suppliers.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fa-solid fa-arrow-left me-1"></i> Back to Suppliers
        </a>
        <button class="btn btn-primary shadow-sm btn-sm fw-semibold" data-bs-toggle="modal" data-bs-target="#addProductModal">
            <i class="fa-solid fa-plus me-1"></i> Add Product to Catalog
        </button>
    </div>
</div>

<div class="card shadow-sm mb-4">
    <div class="card-header bg-white py-3">
        <h5 class="fw-bold mb-0"><i class="fa-solid fa-boxes-packing text-primary me-2"></i> Supplier Offered Product Catalog</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Product Name</th>
                        <th>System SKU</th>
                        <th>Supplier SKU</th>
                        <th class="text-end">Contract Unit Price</th>
                        <th class="text-center">Lead Time</th>
                        <th class="text-center">Min Order Qty</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($supplier->supplierProducts as $sp)
                    <tr>
                        <td class="fw-bold text-primary">{{ $sp->product->name ?? 'Product' }}</td>
                        <td><code>{{ $sp->product->sku ?? '' }}</code></td>
                        <td class="small">{{ $sp->supplier_sku ?? 'N/A' }}</td>
                        <td class="text-end fw-bold text-success">₱{{ number_format($sp->unit_price, 2) }}</td>
                        <td class="text-center small">{{ $sp->lead_time_days }} Days</td>
                        <td class="text-center small">{{ $sp->minimum_order_qty }} units</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">No products mapped to this supplier catalog yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Product Modal -->
<div class="modal fade" id="addProductModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('suppliers.addProduct', $supplier->id) }}" method="POST" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title fw-bold"><i class="fa-solid fa-cart-flatbed me-2 text-primary"></i> Add Catalog Item</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Select Master Product</label>
                    <select name="product_id" class="form-select" required>
                        @foreach($products as $p)
                            <option value="{{ $p->id }}">{{ $p->name }} ({{ $p->sku }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Contract Unit Price (₱)</label>
                    <input type="number" step="0.01" name="unit_price" class="form-control" value="120.00" required>
                </div>
                <div class="row g-2 mb-3">
                    <div class="col-6">
                        <label class="form-label fw-semibold small">Lead Time (Days)</label>
                        <input type="number" name="lead_time_days" class="form-control" value="5" required>
                    </div>
                    <div class="col-6">
                        <label class="form-label fw-semibold small">Min Order Qty</label>
                        <input type="number" name="minimum_order_qty" class="form-control" value="10" required>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary fw-semibold">Save Catalog Item</button>
            </div>
        </form>
    </div>
</div>
@endsection
