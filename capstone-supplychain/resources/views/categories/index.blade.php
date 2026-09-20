@extends('layouts.app')

@section('title', 'Product Categories')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1"><i class="fa-solid fa-tags text-primary me-2"></i> Category Management</h3>
        <p class="text-muted small mb-0">Organize master products into logical supply-chain categories.</p>
    </div>
    <button type="button" class="btn btn-primary btn-sm px-3 shadow-sm" data-bs-toggle="modal" data-bs-target="#createCategoryModal">
        <i class="fa-solid fa-plus me-1"></i> Add New Category
    </button>
</div>

<!-- Categories Table -->
<div class="card shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th>Category Code</th>
                        <th>Category Name</th>
                        <th>Description</th>
                        <th class="text-center">Assigned Products</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $category)
                    <tr>
                        <td class="fw-bold font-monospace text-primary">{{ $category->code }}</td>
                        <td class="fw-semibold text-dark">{{ $category->name }}</td>
                        <td class="text-muted small">{{ $category->description ?? 'No description' }}</td>
                        <td class="text-center">
                            <span class="badge bg-secondary-subtle text-dark border">{{ $category->products_count }} Products</span>
                        </td>
                        <td class="text-end">
                            <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editCategoryModal{{ $category->id }}">
                                <i class="fa-solid fa-pen"></i> Edit
                            </button>

                            <!-- Edit Modal -->
                            <div class="modal fade text-start" id="editCategoryModal{{ $category->id }}" tabindex="-1">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <form action="{{ route('categories.update', $category->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-header">
                                                <h5 class="modal-title fw-bold">Edit Category #{{ $category->id }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label class="form-label fw-semibold">Category Name <span class="text-danger">*</span></label>
                                                    <input type="text" name="name" class="form-control" value="{{ old('name', $category->name) }}" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label fw-semibold">Category Code <span class="text-danger">*</span></label>
                                                    <input type="text" name="code" class="form-control text-uppercase" value="{{ old('code', $category->code) }}" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label fw-semibold">Description</label>
                                                    <textarea name="description" class="form-control" rows="3">{{ old('description', $category->description) }}</textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-save me-1"></i> Update Category</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-4 text-muted">No categories found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($categories->hasPages())
    <div class="card-footer bg-white border-top py-3">
        {{ $categories->links() }}
    </div>
    @endif
</div>

<!-- Create Category Modal -->
<div class="modal fade" id="createCategoryModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('categories.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title fw-bold"><i class="fa-solid fa-plus-circle me-2 text-primary"></i> Create Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Category Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" placeholder="e.g. Raw Metallic Materials" value="{{ old('name') }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Category Code <span class="text-danger">*</span></label>
                        <input type="text" name="code" class="form-control text-uppercase" placeholder="e.g. CAT-RAW" value="{{ old('code') }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Description</label>
                        <textarea name="description" class="form-control" rows="3" placeholder="Brief category description...">{{ old('description') }}</textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary"><i class="fa-solid fa-check me-1"></i> Save Category</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
