@extends('layouts.app')

@section('title', 'Product Catalog')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1"><i class="fa-solid fa-box text-primary me-2"></i> Master Product Catalog</h3>
        <p class="text-muted small mb-0">Master record of SKUs, barcodes, dimensions, stock parameters, and pricing.</p>
    </div>
    <a href="{{ route('products.create') }}" class="btn btn-primary btn-sm px-3 shadow-sm">
        <i class="fa-solid fa-plus me-1"></i> Add New Product
    </a>
</div>

<!-- Search & Filter Card -->
<div class="card shadow-sm mb-4">
    <div class="card-body">
        <form id="productFilterForm" method="GET" action="{{ route('products.index') }}" class="row g-3 align-items-center">
            <!-- Search Input -->
            <div class="col-md-5">
                <div class="input-group input-group-sm">
                    <span class="input-group-text"><i class="fa-solid fa-magnifying-glass"></i></span>
                    <input type="text" id="productSearchInput" name="search" class="form-control" placeholder="Search product name, SKU, barcode, brand..." value="{{ request('search') }}" autocomplete="off">
                </div>
            </div>

            <!-- Category Filter Dropdown -->
            <div class="col-md-3">
                <select id="categorySelect" name="category_id" class="form-select form-select-sm" onchange="submitProductFilterForm()">
                    <option value="">-- Filter by Category --</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ (string)request('category_id') === (string)$cat->id || (string)request('category') === (string)$cat->id || request('category') === $cat->name ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Status Filter Dropdown -->
            <div class="col-md-2">
                <select id="statusSelect" name="status" class="form-select form-select-sm" onchange="submitProductFilterForm()">
                    <option value="">-- All Status --</option>
                    <option value="active" {{ strtolower(request('status')) === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ strtolower(request('status')) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>

            <!-- Filter & Reset Buttons -->
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-dark btn-sm w-100 fw-semibold d-flex align-items-center justify-content-center gap-1">
                    <i class="fa-solid fa-filter"></i> Filter
                </button>
                <button type="button" onclick="resetProductFilters()" class="btn btn-outline-secondary btn-sm fw-semibold d-flex align-items-center justify-content-center gap-1" title="Reset all filters">
                    <i class="fa-solid fa-rotate"></i> Reset
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Product Table -->
<div class="card shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th>Product Info</th>
                        <th>Category</th>
                        <th>Unit Cost</th>
                        <th class="text-center">Stock Thresholds (Min / Safety / Max)</th>
                        <th class="text-center">Vol & Wt (m³ / kg)</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                    <tr>
                        <td>
                            <div class="fw-bold text-dark fs-6">{{ $product->name }}</div>
                            <div class="text-muted small mt-0.5">
                                <span class="badge bg-light text-dark border font-monospace">SKU: {{ $product->sku }}</span>
                                @if($product->barcode)
                                    <span class="badge bg-light text-dark border font-monospace ms-1"><i class="fa-solid fa-barcode me-1"></i>{{ $product->barcode }}</span>
                                @endif
                                @if($product->brand)
                                    <span class="text-secondary ms-1">({{ $product->brand }})</span>
                                @endif
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-info-subtle text-info border border-info px-2.5 py-1.5 fs-7">
                                <i class="fa-solid fa-layer-group me-1 opacity-75"></i>{{ $product->category->name ?? 'Uncategorized' }}
                            </span>
                        </td>
                        <td class="fw-bold text-dark">₱{{ number_format($product->unit_cost, 2) }} / {{ $product->unit_of_measure }}</td>
                        <td class="text-center">
                            <span class="badge bg-warning-subtle text-warning-emphasis border" title="Min Stock">{{ $product->min_stock_level }}</span> /
                            <span class="badge bg-info-subtle text-info border" title="Safety Stock">{{ $product->safety_stock }}</span> /
                            <span class="badge bg-secondary-subtle text-dark border" title="Max Stock">{{ $product->max_stock_level }}</span>
                        </td>
                        <td class="text-center small text-muted">
                            {{ number_format($product->volume_m3, 4) }} m³ | {{ number_format($product->weight_kg, 2) }} kg
                        </td>
                        <td>
                            @if($product->is_active)
                                <span class="badge bg-success-subtle text-success border border-success px-2.5 py-1.5"><i class="fa-solid fa-toggle-on me-1"></i> Active</span>
                            @else
                                <span class="badge bg-danger-subtle text-danger border border-danger px-2.5 py-1.5"><i class="fa-solid fa-toggle-off me-1"></i> Inactive</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <a href="{{ route('products.edit', $product->id) }}" class="btn btn-sm btn-outline-primary" title="Edit Product">
                                <i class="fa-solid fa-pen me-1"></i> Edit
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <div class="py-3">
                                <i class="fa-solid fa-boxes-stacked text-muted display-6 mb-3 d-block opacity-50"></i>
                                <h6 class="fw-bold text-dark mb-1">No products found</h6>
                                <p class="text-muted small mb-0">Try adjusting your search query or category/status filters.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer bg-white border-top py-3">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div class="small text-muted">
                Showing <span class="fw-semibold text-dark">{{ $products->firstItem() ?? 0 }}</span> to <span class="fw-semibold text-dark">{{ $products->lastItem() ?? 0 }}</span> of <span class="fw-semibold text-dark">{{ $products->total() }}</span> products
            </div>
            <div>
                {{ $products->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>

<script>
    let productSearchDebounceTimer;

    function submitProductFilterForm() {
        document.getElementById('productFilterForm').submit();
    }

    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('productSearchInput');
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                clearTimeout(productSearchDebounceTimer);
                productSearchDebounceTimer = setTimeout(function() {
                    submitProductFilterForm();
                }, 400); // 400ms debounce for typing search
            });
        }
    });

    function resetProductFilters() {
        const searchInput = document.getElementById('productSearchInput');
        const categorySelect = document.getElementById('categorySelect');
        const statusSelect = document.getElementById('statusSelect');

        if (searchInput) searchInput.value = '';
        if (categorySelect) categorySelect.selectedIndex = 0;
        if (statusSelect) statusSelect.selectedIndex = 0;

        window.location.href = "{{ route('products.index') }}";
    }
</script>
@endsection
