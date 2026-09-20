@extends('layouts.app')

@section('title', 'Document Tracking & Logistics System (DTRS)')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Document Tracking & Logistics Records (DTRS)</h3>
        <p class="text-muted small mb-0">Secure document repository for POs, Delivery Receipts, Invoices, and Supplier Logistics Records.</p>
    </div>
    <button class="btn btn-primary shadow-sm btn-sm fw-semibold" data-bs-toggle="modal" data-bs-target="#uploadDocModal">
        <i class="fa-solid fa-cloud-arrow-up me-1"></i> Upload Logistics Document
    </button>
</div>

<div class="card shadow-sm">
    <div class="card-header bg-white py-3">
        <h5 class="fw-bold mb-0"><i class="fa-solid fa-folder-closed text-primary me-2"></i> Document Vault Repository</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Doc Number</th>
                        <th>Document Title</th>
                        <th>Type</th>
                        <th>File Info</th>
                        <th>Uploaded By</th>
                        <th>Linked Module</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($documents as $doc)
                    <tr>
                        <td class="fw-bold text-primary">{{ $doc->document_number }}</td>
                        <td class="fw-semibold">{{ $doc->title }}</td>
                        <td>
                            <span class="badge bg-dark">{{ $doc->document_type }}</span>
                        </td>
                        <td class="small">
                            <div>{{ $doc->file_name }}</div>
                            <div class="text-muted">{{ number_format($doc->file_size_bytes / 1024, 1) }} KB • {{ $doc->mime_type }}</div>
                        </td>
                        <td class="small">{{ $doc->uploadedBy->name ?? 'Staff' }}</td>
                        <td>
                            @forelse($doc->links as $link)
                                <span class="badge bg-info text-dark">Linked PO #{{ $link->linkable->po_number ?? 'PO' }}</span>
                            @empty
                                <span class="text-muted small">Unlinked</span>
                            @endforelse
                        </td>
                        <td>
                            <a href="{{ route('documents.download', $doc->id) }}" class="btn btn-outline-primary btn-sm">
                                <i class="fa-solid fa-download me-1"></i> Download
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">No logistics documents uploaded to DTRS repository yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer bg-white">
        {{ $documents->links() }}
    </div>
</div>

<!-- Upload Document Modal -->
<div class="modal fade" id="uploadDocModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('documents.store') }}" method="POST" enctype="multipart/form-data" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title fw-bold"><i class="fa-solid fa-file-arrow-up text-primary me-2"></i> Upload Document to DTRS</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Document Title</label>
                    <input type="text" name="title" class="form-control" placeholder="e.g., Signed Delivery Receipt - PO #9012" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Document Category / Type</label>
                    <select name="document_type" class="form-select" required>
                        <option value="DELIVERY_RECEIPT">Delivery Receipt (DR)</option>
                        <option value="PO">Purchase Order Copy</option>
                        <option value="INVOICE">Supplier Invoice</option>
                        <option value="SUPPLIER_DOC">Supplier Accreditation File</option>
                        <option value="LOGISTICS_DOC">Logistics & Customs Clearance</option>
                        <option value="OTHER">Other Records</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Link to Purchase Order (Optional)</label>
                    <select name="purchase_order_id" class="form-select">
                        <option value="">-- None --</option>
                        @foreach($purchaseOrders as $po)
                            <option value="{{ $po->id }}">{{ $po->po_number }} ({{ $po->supplier->company_name ?? '' }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Select File (PDF, PNG, JPG, DOCX - Max 10MB)</label>
                    <input type="file" name="file" class="form-control" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary fw-semibold">Secure Upload to DTRS</button>
            </div>
        </form>
    </div>
</div>
@endsection
