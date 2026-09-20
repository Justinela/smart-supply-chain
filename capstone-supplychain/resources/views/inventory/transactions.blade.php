@extends('layouts.app')

@section('title', 'Transaction Ledger History')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Immutable Inventory Transaction Ledger</h3>
        <p class="text-muted small mb-0">Complete audit trail of all Stock-In, Stock-Out, Transfer, and Adjustment transactions.</p>
    </div>
    <a href="{{ route('inventory.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="fa-solid fa-arrow-left me-1"></i> Back to Inventory
    </a>
</div>

<div class="card shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Txn ID / Date</th>
                        <th>Type</th>
                        <th>Product Info</th>
                        <th>Location</th>
                        <th class="text-center">Qty Before</th>
                        <th class="text-center">Change</th>
                        <th class="text-center">Qty After</th>
                        <th>User</th>
                        <th>Notes / Reference</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($transactions as $t)
                    <tr>
                        <td>
                            <div class="fw-semibold">#TXN-{{ $t->id }}</div>
                            <div class="text-muted small">{{ $t->created_at ? \Carbon\Carbon::parse($t->created_at)->format('Y-m-d H:i:s') : 'N/A' }}</div>
                        </td>
                        <td>
                            @if($t->type == 'STOCK_IN')
                                <span class="badge bg-success"><i class="fa-solid fa-arrow-down me-1"></i> Stock In</span>
                            @elseif($t->type == 'STOCK_OUT')
                                <span class="badge bg-danger"><i class="fa-solid fa-arrow-up me-1"></i> Stock Out</span>
                            @else
                                <span class="badge bg-info"><i class="fa-solid fa-right-left me-1"></i> {{ $t->type }}</span>
                            @endif
                        </td>
                        <td>
                            <div class="fw-semibold">{{ $t->product->name ?? 'Product' }}</div>
                            <div class="text-muted small">{{ $t->product->sku ?? '' }}</div>
                        </td>
                        <td class="small">{{ $t->storageLocation->code ?? 'N/A' }}</td>
                        <td class="text-center text-muted">{{ $t->quantity_before }}</td>
                        <td class="text-center fw-bold {{ $t->quantity_change > 0 ? 'text-success' : 'text-danger' }}">
                            {{ $t->quantity_change > 0 ? '+' : '' }}{{ $t->quantity_change }}
                        </td>
                        <td class="text-center fw-bold text-dark">{{ $t->quantity_after }}</td>
                        <td class="small">{{ $t->user->name ?? 'System' }}</td>
                        <td class="small text-muted">{{ $t->notes ?? '-' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer bg-white">
        {{ $transactions->links() }}
    </div>
</div>
@endsection
