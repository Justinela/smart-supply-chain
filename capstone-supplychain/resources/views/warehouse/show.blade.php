@extends('layouts.app')

@section('title', 'Warehouse Storage Bins - ' . $warehouse->name)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">{{ $warehouse->name }} ({{ $warehouse->code }})</h3>
        <p class="text-muted small mb-0">{{ $warehouse->address }} • {{ number_format($warehouse->current_occupancy_m3, 2) }} / {{ number_format($warehouse->total_capacity_m3, 2) }} m³ Occupied</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('warehouse.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fa-solid fa-arrow-left me-1"></i> Back to Hubs
        </a>
        <button class="btn btn-primary shadow-sm btn-sm fw-semibold" data-bs-toggle="modal" data-bs-target="#newBinModal">
            <i class="fa-solid fa-plus me-1"></i> Add Storage Bin
        </button>
    </div>
</div>

<div class="card shadow-sm mb-4">
    <div class="card-header bg-white py-3">
        <h5 class="fw-bold mb-0"><i class="fa-solid fa-boxes-stacked text-primary me-2"></i> Storage Locations & Bins Matrix</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Bin Code</th>
                        <th>Zone</th>
                        <th>Aisle / Rack / Shelf</th>
                        <th class="text-center">Weight Limit</th>
                        <th class="text-center">Volume Occupied</th>
                        <th class="text-center">Stored Items</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($warehouse->storageLocations as $loc)
                    <tr>
                        <td class="fw-bold text-primary">{{ $loc->code }}</td>
                        <td><span class="badge bg-dark">{{ $loc->zone }}</span></td>
                        <td class="small">{{ $loc->aisle }} • {{ $loc->rack }} • {{ $loc->shelf }}</td>
                        <td class="text-center small fw-semibold">{{ number_format($loc->max_weight_kg, 1) }} kg</td>
                        <td class="text-center small">
                            {{ number_format($loc->occupied_volume_m3, 3) }} / {{ number_format($loc->max_volume_m3, 3) }} m³
                            <div class="progress mt-1" style="height: 6px;">
                                <div class="progress-bar bg-info" style="width: {{ $loc->max_volume_m3 > 0 ? round(($loc->occupied_volume_m3 / $loc->max_volume_m3)*100) : 0 }}%"></div>
                            </div>
                        </td>
                        <td class="text-center">
                            <span class="badge bg-secondary">{{ $loc->inventories->count() }} SKUs</span>
                        </td>
                        <td>
                            <span class="badge bg-success">Active</span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">No storage bins registered for this warehouse yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Storage Bin Modal -->
<div class="modal fade" id="newBinModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('warehouse.storeLocation', $warehouse->id) }}" method="POST" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title fw-bold"><i class="fa-solid fa-box text-primary me-2"></i> Add Storage Bin Location</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Storage Location Code</label>
                    <input type="text" name="code" class="form-control" placeholder="WH1-A-01-C4" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Zone Classification</label>
                    <input type="text" name="zone" class="form-control" value="Zone A (General)" required>
                </div>
                <div class="row g-2 mb-3">
                    <div class="col-4">
                        <label class="form-label fw-semibold small">Aisle</label>
                        <input type="text" name="aisle" class="form-control" value="Aisle 01" required>
                    </div>
                    <div class="col-4">
                        <label class="form-label fw-semibold small">Rack</label>
                        <input type="text" name="rack" class="form-control" value="Rack 01" required>
                    </div>
                    <div class="col-4">
                        <label class="form-label fw-semibold small">Shelf</label>
                        <input type="text" name="shelf" class="form-control" value="Shelf C4" required>
                    </div>
                </div>
                <div class="row g-2 mb-3">
                    <div class="col-6">
                        <label class="form-label fw-semibold small">Max Weight (kg)</label>
                        <input type="number" step="0.1" name="max_weight_kg" class="form-control" value="500.0" required>
                    </div>
                    <div class="col-6">
                        <label class="form-label fw-semibold small">Max Volume (m³)</label>
                        <input type="number" step="0.01" name="max_volume_m3" class="form-control" value="4.50" required>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary fw-semibold">Save Bin Location</button>
            </div>
        </form>
    </div>
</div>
@endsection
