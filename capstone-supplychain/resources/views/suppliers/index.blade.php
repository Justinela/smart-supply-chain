@extends('layouts.app')

@section('title', 'Supplier / Vendor Management')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Supplier & Vendor Management</h3>
        <p class="text-muted small mb-0">Manage accredited vendors, supplier catalogs, price lists, and performance evaluations.</p>
    </div>
    <button class="btn btn-primary shadow-sm btn-sm fw-semibold" data-bs-toggle="modal" data-bs-target="#newSupplierModal">
        <i class="fa-solid fa-plus me-1"></i> Register Supplier
    </button>
</div>

<div class="card shadow-sm">
    <div class="card-header bg-white py-3">
        <h5 class="fw-bold mb-0"><i class="fa-solid fa-truck-field text-primary me-2"></i> Accredited Vendors Directory</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Code</th>
                        <th>Company Name</th>
                        <th>Contact Person</th>
                        <th>Email & Phone</th>
                        <th class="text-center">Catalog SKUs</th>
                        <th class="text-center">Performance Rating</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($suppliers as $sup)
                    <tr>
                        <td class="fw-bold text-primary">{{ $sup->code }}</td>
                        <td>
                            <div class="fw-semibold">{{ $sup->company_name }}</div>
                            <div class="text-muted small">TAX ID: {{ $sup->tax_id ?? 'N/A' }}</div>
                        </td>
                        <td class="fw-semibold">{{ $sup->contact_person }}</td>
                        <td class="small">
                            <div><i class="fa-solid fa-envelope me-1 text-muted"></i> {{ $sup->email }}</div>
                            <div><i class="fa-solid fa-phone me-1 text-muted"></i> {{ $sup->phone }}</div>
                        </td>
                        <td class="text-center">
                            <span class="badge bg-secondary">{{ $sup->supplier_products_count }} Products</span>
                        </td>
                        <td class="text-center">
                            <span class="badge bg-success-subtle text-success fs-6">★ {{ number_format($sup->rating_score, 2) }} / 5.0</span>
                        </td>
                        <td>
                            <a href="{{ route('suppliers.show', $sup->id) }}" class="btn btn-outline-primary btn-sm">
                                View Catalog <i class="fa-solid fa-arrow-right ms-1"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">No suppliers registered yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer bg-white">
        {{ $suppliers->links() }}
    </div>
</div>

<!-- New Supplier Modal -->
<div class="modal fade" id="newSupplierModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('suppliers.store') }}" method="POST" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title fw-bold"><i class="fa-solid fa-truck text-primary me-2"></i> Register New Supplier</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Supplier Code</label>
                    <input type="text" name="code" class="form-control" placeholder="SUP-NEXUS-01" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Company Name</label>
                    <input type="text" name="company_name" class="form-control" placeholder="Nexus Industrial Supplies Inc." required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Contact Person</label>
                    <input type="text" name="contact_person" class="form-control" placeholder="Arthur Pendelton" required>
                </div>
                <div class="row g-2 mb-3">
                    <div class="col-6">
                        <label class="form-label fw-semibold small">Email Address</label>
                        <input type="email" name="email" class="form-control" placeholder="sales@nexus.test" required>
                    </div>
                    <div class="col-6">
                        <label class="form-label fw-semibold small">Phone Number</label>
                        <input type="text" name="phone" class="form-control" placeholder="+63 917 111 2233" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Tax ID / TIN</label>
                    <input type="text" name="tax_id" class="form-control" placeholder="123-456-789-000">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary fw-semibold">Save Supplier</button>
            </div>
        </form>
    </div>
</div>
@endsection
