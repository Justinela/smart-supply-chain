@extends('layouts.app')

@section('title', 'Edit Product')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1"><i class="fa-solid fa-pen-to-square text-primary me-2"></i> Edit Product Record</h3>
        <p class="text-muted small mb-0">Update specifications, stock thresholds, and dimensions for {{ $product->name }}.</p>
    </div>
    <a href="{{ route('products.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="fa-solid fa-arrow-left me-1"></i> Back to Product Catalog
    </a>
</div>

<div class="card shadow-sm col-lg-9 mx-auto">
    <div class="card-header bg-white py-3">
        <h5 class="fw-bold mb-0">Product #{{ $product->id }} - {{ $product->sku }}</h5>
    </div>
    <div class="card-body">
        @if($errors->any())
            <div class="alert alert-danger py-2 small mb-3">
                <ul class="mb-0 ps-3">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('products.update', $product->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Product Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $product->name) }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Category <span class="text-danger">*</span></label>
                    <select name="category_id" class="form-select" required>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }} ({{ $cat->code }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">SKU Code <span class="text-danger">*</span></label>
                    <input type="text" name="sku" class="form-control text-uppercase" value="{{ old('sku', $product->sku) }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Barcode (Optional)</label>
                    <input type="text" name="barcode" class="form-control" value="{{ old('barcode', $product->barcode) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Brand / Manufacturer</label>
                    <input type="text" name="brand" class="form-control" value="{{ old('brand', $product->brand) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Unit of Measure <span class="text-danger">*</span></label>
                    <input type="text" name="unit_of_measure" class="form-control" value="{{ old('unit_of_measure', $product->unit_of_measure) }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Unit Cost (₱) <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" name="unit_cost" class="form-control" value="{{ old('unit_cost', $product->unit_cost) }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Active Status</label>
                    <div class="form-check form-switch mt-2">
                        <input type="hidden" name="is_active" value="0">
                        <input class="form-check-input" type="checkbox" name="is_active" value="1" id="isActiveSwitch" {{ old('is_active', $product->is_active) ? 'checked' : '' }}>
                        <label class="form-check-label fw-semibold text-theme-primary" for="isActiveSwitch" id="isActiveSwitchLabel">
                            {{ old('is_active', $product->is_active) ? 'Active Product' : 'Inactive Product' }}
                        </label>
                    </div>
                </div>

                <hr class="my-3">
                <h6 class="fw-bold text-primary mb-2"><i class="fa-solid fa-sliders me-1"></i> Inventory Thresholds & Physical Dimensions</h6>

                <div class="col-md-3">
                    <label class="form-label fw-semibold">Min Stock Level <span class="text-danger">*</span></label>
                    <input type="number" name="min_stock_level" class="form-control" value="{{ old('min_stock_level', $product->min_stock_level) }}" required min="0">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Reorder Point <span class="text-danger">*</span></label>
                    <input type="number" name="reorder_point" class="form-control" value="{{ old('reorder_point', $product->reorder_point) }}" required min="0">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Safety Stock <span class="text-danger">*</span></label>
                    <input type="number" name="safety_stock" class="form-control" value="{{ old('safety_stock', $product->safety_stock) }}" required min="0">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Max Stock Level <span class="text-danger">*</span></label>
                    <input type="number" name="max_stock_level" class="form-control" value="{{ old('max_stock_level', $product->max_stock_level) }}" required min="1">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Unit Weight (kg) <span class="text-danger">*</span></label>
                    <input type="number" step="0.001" name="weight_kg" class="form-control" value="{{ old('weight_kg', $product->weight_kg) }}" required min="0">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Unit Volume (m³) <span class="text-danger">*</span></label>
                    <input type="number" step="0.0001" name="volume_m3" class="form-control" value="{{ old('volume_m3', $product->volume_m3) }}" required min="0">
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                <a href="{{ route('products.index') }}" class="btn btn-light">Cancel</a>
                <button type="submit" class="btn btn-primary px-4"><i class="fa-solid fa-save me-1"></i> Save Changes</button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const switchEl = document.getElementById('isActiveSwitch');
        const labelEl = document.getElementById('isActiveSwitchLabel');
        if (switchEl && labelEl) {
            switchEl.addEventListener('change', function() {
                labelEl.textContent = this.checked ? 'Active Product' : 'Inactive Product';
            });
        }
    });
</script>
@endsection
