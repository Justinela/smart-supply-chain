@extends('layouts.app')

@section('title', 'User Management')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1"><i class="fa-solid fa-users-gear text-primary me-2"></i> User Management</h3>
        <p class="text-muted small mb-0">System access control, role assignment, account status, and password administration.</p>
    </div>
    <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-sm px-3 shadow-sm">
        <i class="fa-solid fa-user-plus me-1"></i> Create New User
    </a>
</div>

<!-- Search & Filter Card -->
<div class="card shadow-sm mb-4">
    <div class="card-body">
        <form id="userFilterForm" method="GET" action="{{ route('admin.users.index') }}" class="row g-3 align-items-center">
            <!-- Search Box -->
            <div class="col-md-5">
                <div class="input-group input-group-sm">
                    <span class="input-group-text"><i class="fa-solid fa-magnifying-glass"></i></span>
                    <input type="text" id="searchInput" name="search" class="form-control" placeholder="Search by username, name, or email..." value="{{ request('search') }}" autocomplete="off">
                </div>
            </div>

            <!-- Role Filter Dropdown -->
            <div class="col-md-3">
                <select id="roleSelect" name="role_id" class="form-select form-select-sm" onchange="submitFilterForm()">
                    <option value="">-- Filter by Role --</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->id }}" {{ (string)request('role_id') === (string)$role->id || (string)request('role') === (string)$role->id || request('role') === $role->display_name || request('role') === $role->name ? 'selected' : '' }}>
                            {{ $role->display_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Status Filter Dropdown -->
            <div class="col-md-2">
                <select id="statusSelect" name="status" class="form-select form-select-sm" onchange="submitFilterForm()">
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
                <button type="button" onclick="resetUserFilters()" class="btn btn-outline-secondary btn-sm fw-semibold d-flex align-items-center justify-content-center gap-1" title="Reset all filters">
                    <i class="fa-solid fa-rotate"></i> Reset
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Users Table -->
<div class="card shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th>User Info & Username</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Last Login</th>
                        <th>Created At</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <div class="rounded-circle bg-primary-subtle text-primary fw-bold d-flex align-items-center justify-content-center shadow-sm" style="width:40px; height:40px; font-size: 1.1rem;">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <div>
                                    <div class="fw-semibold text-dark">{{ $user->name }}</div>
                                    <div class="text-muted small d-flex align-items-center gap-1 mt-0.5">
                                        @if($user->username)
                                            <span class="badge bg-secondary-subtle text-secondary me-1"><i class="fa-solid fa-at me-0.5"></i>{{ $user->username }}</span>
                                        @endif
                                        <span>{{ $user->email }}</span>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td>
                            @php
                                $roleName = strtolower($user->role->name ?? '');
                                $badgeClass = match($roleName) {
                                    'admin' => 'bg-indigo text-white',
                                    'warehouse_staff' => 'bg-primary text-white',
                                    'procurement_staff' => 'bg-success text-white',
                                    'management' => 'bg-warning text-dark',
                                    default => 'bg-secondary text-white',
                                };
                            @endphp
                            <span class="badge {{ $badgeClass }} px-2.5 py-1.5 fs-7 shadow-sm">
                                <i class="fa-solid fa-user-shield me-1 opacity-75"></i>{{ $user->role_display_name }}
                            </span>
                        </td>
                        <td>
                            @if($user->is_active)
                                <span class="badge bg-success-subtle text-success border border-success px-2.5 py-1.5"><i class="fa-solid fa-circle-check me-1"></i> Active</span>
                            @else
                                <span class="badge bg-danger-subtle text-danger border border-danger px-2.5 py-1.5"><i class="fa-solid fa-circle-xmark me-1"></i> Inactive</span>
                            @endif
                        </td>
                        <td class="small text-muted">
                            {{ $user->last_login_at ? $user->last_login_at->format('M d, Y H:i') : 'Never' }}
                        </td>
                        <td class="small text-muted">{{ $user->created_at ? $user->created_at->format('Y-m-d') : 'N/A' }}</td>
                        <td class="text-end">
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-outline-primary" title="Edit User">
                                    <i class="fa-solid fa-pen"></i>
                                </a>
                                @if($user->id !== Auth::id())
                                <form action="{{ route('admin.users.toggleStatus', $user->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Toggle status for user {{ $user->name }}?')">
                                    @csrf
                                    <button type="submit" class="btn {{ $user->is_active ? 'btn-outline-warning' : 'btn-outline-success' }}" title="{{ $user->is_active ? 'Deactivate Account' : 'Activate Account' }}">
                                        <i class="fa-solid {{ $user->is_active ? 'fa-user-slash' : 'fa-user-check' }}"></i>
                                    </button>
                                </form>
                                @endif
                                <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#resetPassModal{{ $user->id }}" title="Reset Password">
                                    <i class="fa-solid fa-key"></i>
                                </button>
                            </div>

                            <!-- Reset Password Modal -->
                            <div class="modal fade text-start" id="resetPassModal{{ $user->id }}" tabindex="-1">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <form action="{{ route('admin.users.resetPassword', $user->id) }}" method="POST">
                                            @csrf
                                            <div class="modal-header">
                                                <h5 class="modal-title fw-bold"><i class="fa-solid fa-key me-2 text-danger"></i> Reset Password for {{ $user->name }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label class="form-label fw-semibold">New Password</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i class="fa-solid fa-lock"></i></span>
                                                        <input type="password" id="resetPassInput{{ $user->id }}" name="password" class="form-control" required minlength="8" placeholder="At least 8 characters">
                                                        <button class="btn btn-outline-secondary toggle-password-btn" type="button" onclick="togglePasswordVisibility('resetPassInput{{ $user->id }}', this)" title="Show / Hide Password">
                                                            <i class="fa-solid fa-eye"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label fw-semibold">Confirm Password</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i class="fa-solid fa-check-double"></i></span>
                                                        <input type="password" id="resetPassConfirmInput{{ $user->id }}" name="password_confirmation" class="form-control" required minlength="8" placeholder="Repeat new password">
                                                        <button class="btn btn-outline-secondary toggle-password-btn" type="button" onclick="togglePasswordVisibility('resetPassConfirmInput{{ $user->id }}', this)" title="Show / Hide Password">
                                                            <i class="fa-solid fa-eye"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-danger"><i class="fa-solid fa-check me-1"></i> Reset Password</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <div class="py-3">
                                <i class="fa-solid fa-users-slash text-muted display-6 mb-3 d-block opacity-50"></i>
                                <h6 class="fw-bold text-dark mb-1">No users found</h6>
                                <p class="text-muted small mb-0">Try adjusting your search query or role/status filters.</p>
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
                Showing <span class="fw-semibold text-dark">{{ $users->firstItem() ?? 0 }}</span> to <span class="fw-semibold text-dark">{{ $users->lastItem() ?? 0 }}</span> of <span class="fw-semibold text-dark">{{ $users->total() }}</span> users
            </div>
            <div>
                {{ $users->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>

<script>
    let searchDebounceTimer;

    function submitFilterForm() {
        document.getElementById('userFilterForm').submit();
    }

    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                clearTimeout(searchDebounceTimer);
                searchDebounceTimer = setTimeout(function() {
                    submitFilterForm();
                }, 400); // 400ms debounce for smooth search typing
            });
        }
    });

    function resetUserFilters() {
        const searchInput = document.getElementById('searchInput');
        const roleSelect = document.getElementById('roleSelect');
        const statusSelect = document.getElementById('statusSelect');

        if (searchInput) searchInput.value = '';
        if (roleSelect) roleSelect.selectedIndex = 0;
        if (statusSelect) statusSelect.selectedIndex = 0;

        window.location.href = "{{ route('admin.users.index') }}";
    }

    function togglePasswordVisibility(inputId, btn) {
        const input = document.getElementById(inputId);
        if (!input) return;
        const icon = btn.querySelector('i');
        if (input.type === 'password') {
            input.type = 'text';
            if (icon) {
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            }
        } else {
            input.type = 'password';
            if (icon) {
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
    }
</script>
@endsection
