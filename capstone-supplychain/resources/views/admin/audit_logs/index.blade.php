@extends('layouts.app')

@section('title', 'System Audit Trail')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1"><i class="fa-solid fa-shield-halved text-info me-2"></i> System Audit Trail</h3>
        <p class="text-muted small mb-0">Append-only security & transactional audit trail of all user actions across modules.</p>
    </div>
</div>

<!-- Search & Filter Card -->
<div class="card shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.audit-logs.index') }}" class="row g-3">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Search action, module, or IP..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <select name="module" class="form-select form-select-sm">
                    <option value="">-- Filter by Module --</option>
                    @foreach($modules as $mod)
                        <option value="{{ $mod }}" {{ request('module') == $mod ? 'selected' : '' }}>{{ $mod }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <select name="user_id" class="form-select form-select-sm">
                    <option value="">-- Filter by User --</option>
                    @foreach($users as $u)
                        <option value="{{ $u->id }}" {{ request('user_id') == $u->id ? 'selected' : '' }}>{{ $u->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-dark btn-sm w-100"><i class="fa-solid fa-filter me-1"></i> Filter</button>
                <a href="{{ route('admin.audit-logs.index') }}" class="btn btn-outline-secondary btn-sm"><i class="fa-solid fa-rotate me-1"></i> Reset</a>
            </div>
        </form>
    </div>
</div>

<!-- Audit Log Table -->
<div class="card shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th>Timestamp</th>
                        <th>User</th>
                        <th>Action</th>
                        <th>Module</th>
                        <th>IP Address</th>
                        <th>Details (Before / After)</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                    <tr>
                        <td class="small text-muted fw-semibold">
                            {{ $log->created_at ? \Carbon\Carbon::parse($log->created_at)->format('Y-m-d H:i:s') : 'N/A' }}
                        </td>
                        <td>
                            <div class="fw-semibold text-dark">{{ $log->user->name ?? 'System / Anonymous' }}</div>
                            <div class="text-muted small">{{ $log->user->email ?? '-' }}</div>
                        </td>
                        <td>
                            <span class="badge bg-dark font-monospace">{{ $log->action }}</span>
                        </td>
                        <td>
                            <span class="badge bg-info-subtle text-info border border-info">{{ $log->module }}</span>
                        </td>
                        <td class="small font-monospace text-muted">{{ $log->ip_address ?? '127.0.0.1' }}</td>
                        <td class="small">
                            {!! $log->formattedDescription() !!}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">No audit logs found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($logs->hasPages())
    <div class="card-footer bg-white border-top py-3">
        {{ $logs->links() }}
    </div>
    @endif
</div>
@endsection
