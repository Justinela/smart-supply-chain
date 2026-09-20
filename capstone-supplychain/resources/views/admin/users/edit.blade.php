@extends('layouts.app')

@section('title', 'Edit User')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1"><i class="fa-solid fa-pen-to-square text-primary me-2"></i> Edit User Account</h3>
        <p class="text-muted small mb-0">Modify user profile, system role, account status, and credentials.</p>
    </div>
    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="fa-solid fa-arrow-left me-1"></i> Back to User List
    </a>
</div>

<div class="card shadow-sm col-lg-8 mx-auto">
    <div class="card-header bg-white py-3">
        <h5 class="fw-bold mb-0"><i class="fa-solid fa-user-gear me-2 text-primary"></i> Edit Profile for {{ $user->name }} (#{{ $user->id }})</h5>
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

        <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row g-3">
                <!-- Full Name -->
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Full Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                </div>

                <!-- Username -->
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Username <span class="text-muted fw-normal">(system login)</span></label>
                    <input type="text" name="username" class="form-control" value="{{ old('username', $user->username) }}" placeholder="e.g. warehouse01">
                </div>

                <!-- Email Address -->
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Email Address <span class="text-danger">*</span></label>
                    <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                </div>

                <!-- Role -->
                <div class="col-md-6">
                    <label class="form-label fw-semibold">System Role <span class="text-danger">*</span></label>
                    <select name="role_id" class="form-select" required>
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}" {{ old('role_id', $user->role_id) == $role->id ? 'selected' : '' }}>
                                {{ $role->display_name }} ({{ $role->description }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Account Status -->
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Account Status <span class="text-danger">*</span></label>
                    <select name="is_active" class="form-select" required>
                        <option value="1" {{ old('is_active', $user->is_active ? '1' : '0') == '1' ? 'selected' : '' }}>Active (Can log in)</option>
                        <option value="0" {{ old('is_active', $user->is_active ? '1' : '0') == '0' ? 'selected' : '' }}>Inactive (Access blocked)</option>
                    </select>
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                <a href="{{ route('admin.users.index') }}" class="btn btn-light">Cancel</a>
                <button type="submit" class="btn btn-primary px-4"><i class="fa-solid fa-floppy-disk me-1"></i> Save Changes</button>
            </div>
        </form>
    </div>
</div>
@endsection
