@extends('layouts.app')

@section('title', 'Smart Warehousing System (SWS)')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1"><i class="fa-solid fa-warehouse text-primary me-2"></i> Smart Warehousing System (SWS)</h3>
        <p class="text-muted small mb-0">Monitor warehouse capacity thresholds, zone allocations, and storage location occupancy.</p>
    </div>
    <button class="btn btn-primary shadow-sm btn-sm px-3 fw-semibold" data-bs-toggle="modal" data-bs-target="#newWarehouseModal">
        <i class="fa-solid fa-plus me-1"></i> Register Warehouse Hub
    </button>
</div>

<div class="row g-4">
    @foreach($warehouses as $wh)
    <div class="col-md-6">
        <div class="card h-100 shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                <div>
                    <div class="d-flex align-items-center gap-2">
                        <h5 class="fw-bold mb-0 text-primary">{{ $wh->name }}</h5>
                        <span class="badge {{ $wh->capacityBadgeClass() }} small">{{ $wh->capacityStatusLabel() }}</span>
                    </div>
                    <div class="text-muted small mt-1">Code: <strong class="font-monospace">{{ $wh->code }}</strong> • {{ $wh->address }}</div>
                </div>
                <a href="{{ route('warehouse.show', $wh->id) }}" class="btn btn-outline-primary btn-sm">
                    Manage Locations <i class="fa-solid fa-arrow-right ms-1"></i>
                </a>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center small mb-1 fw-semibold">
                        <span>Storage Volume Utilization</span>
                        <span>{{ number_format($wh->current_occupancy_m3, 2) }} / {{ number_format($wh->total_capacity_m3, 2) }} m³ ({{ $wh->occupancyPercentage() }}%)</span>
                    </div>
                    <div class="progress" style="height: 12px;">
                        <div class="progress-bar {{ $wh->occupancyPercentage() >= 91 ? 'bg-danger' : ($wh->occupancyPercentage() >= 81 ? 'bg-warning' : ($wh->occupancyPercentage() >= 61 ? 'bg-info' : 'bg-success')) }}" style="width: {{ min(100, $wh->occupancyPercentage()) }}%"></div>
                    </div>
                </div>

                <div class="row text-center g-2 mt-2">
                    <div class="col-6">
                        <div class="p-2 bg-light rounded border">
                            <div class="text-muted small">Storage Locations / Bins</div>
                            <div class="fw-bold fs-5 text-dark">{{ $wh->storage_locations_count }}</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-2 bg-light rounded border">
                            <div class="text-muted small">Available Space</div>
                            <div class="fw-bold fs-5 text-success">{{ number_format(max(0, $wh->total_capacity_m3 - $wh->current_occupancy_m3), 2) }} m³</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>

<!-- New Warehouse Modal -->
<div class="modal fade" id="newWarehouseModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form action="{{ route('warehouse.store') }}" method="POST" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title fw-bold"><i class="fa-solid fa-warehouse text-primary me-2"></i> Register New Warehouse Hub</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Warehouse Code <span class="text-danger">*</span></label>
                    <input type="text" name="code" class="form-control text-uppercase" placeholder="e.g. WH-EAST-03" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Warehouse Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control" placeholder="e.g. East District Logistics Hub" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Physical Address</label>
                    <textarea name="address" class="form-control" rows="2" placeholder="Street address, City"></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Total Storage Capacity (m³) <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" name="total_capacity_m3" class="form-control" value="1500.00" required min="1">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary fw-semibold"><i class="fa-solid fa-check me-1"></i> Save Warehouse</button>
            </div>
        </form>
    </div>
</div>
@endsection
