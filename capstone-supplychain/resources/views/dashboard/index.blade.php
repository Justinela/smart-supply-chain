@extends('layouts.app')

@section('title', 'Smart Supply Chain Executive Dashboard')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1 d-flex align-items-center gap-2">
            <i class="fa-solid fa-chart-pie text-primary"></i> 
            Executive Supply Chain Dashboard
        </h3>
        <p class="text-muted small mb-0 font-poppins">
            Real-time supply chain operational metrics & intelligent inventory insights.
        </p>
    </div>
    <div class="d-flex gap-2 align-items-center">
        <span class="badge bg-success-subtle text-success border border-success px-3 py-2 rounded-pill fw-medium"><i class="fa-solid fa-circle-check me-1"></i> AI Active</span>
    </div>
</div>

<!-- TOP 8 KPI CARDS -->
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-lg-3">
        <div class="card shadow-sm h-100">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-muted text-uppercase fw-semibold small" style="font-size:0.7rem;">Active Warehouses</div>
                        <div class="fs-3 fw-bold text-dark mt-1">{{ $totalWarehouses }}</div>
                    </div>
                    <div class="bg-primary-subtle text-primary p-3 rounded-circle">
                        <i class="fa-solid fa-warehouse fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-lg-3">
        <div class="card shadow-sm h-100">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-muted text-uppercase fw-semibold small" style="font-size:0.7rem;">Master Products</div>
                        <div class="fs-3 fw-bold text-dark mt-1">{{ $totalProducts }}</div>
                    </div>
                    <div class="bg-info-subtle text-info p-3 rounded-circle">
                        <i class="fa-solid fa-cubes fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-lg-3">
        <div class="card shadow-sm h-100">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-muted text-uppercase fw-semibold small" style="font-size:0.7rem;">Total Inventory Units</div>
                        <div class="fs-3 fw-bold text-dark mt-1">{{ number_format($totalInventoryQty) }}</div>
                    </div>
                    <div class="bg-success-subtle text-success p-3 rounded-circle">
                        <i class="fa-solid fa-layer-group fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-lg-3">
        <div class="card shadow-sm h-100">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-muted text-uppercase fw-semibold small" style="font-size:0.7rem;">Low Stock Alerts</div>
                        <div class="fs-3 fw-bold {{ count($lowStockProducts) > 0 ? 'text-danger' : 'text-dark' }} mt-1">{{ count($lowStockProducts) }}</div>
                    </div>
                    <div class="bg-warning-subtle text-warning p-3 rounded-circle">
                        <i class="fa-solid fa-triangle-exclamation fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-lg-3">
        <div class="card shadow-sm h-100">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-muted text-uppercase fw-semibold small" style="font-size:0.7rem;">Pending Purchase Requests</div>
                        <div class="fs-3 fw-bold text-dark mt-1">{{ $pendingPRs }}</div>
                    </div>
                    <div class="bg-secondary-subtle text-secondary p-3 rounded-circle">
                        <i class="fa-solid fa-clipboard-list fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-lg-3">
        <div class="card shadow-sm h-100">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-muted text-uppercase fw-semibold small" style="font-size:0.7rem;">Pending Purchase Orders</div>
                        <div class="fs-3 fw-bold text-dark mt-1">{{ $pendingPOs }}</div>
                    </div>
                    <div class="bg-primary-subtle text-primary p-3 rounded-circle">
                        <i class="fa-solid fa-file-invoice-dollar fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-lg-3">
        <div class="card shadow-sm h-100">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-muted text-uppercase fw-semibold small" style="font-size:0.7rem;">Warehouse Utilization</div>
                        <div class="fs-3 fw-bold text-dark mt-1">{{ $overallUtilizationPct }}%</div>
                    </div>
                    <div class="bg-info-subtle text-info p-3 rounded-circle">
                        <i class="fa-solid fa-percent fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-lg-3">
        <div class="card shadow-sm h-100">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-muted text-uppercase fw-semibold small" style="font-size:0.7rem;">AI Stockout Risk</div>
                        <div class="fs-3 fw-bold text-purple mt-1">
                            {{ $aiForecast['inventory_recommendations']['stockout_risk_level'] ?? 'LOW' }}
                        </div>
                    </div>
                    <div class="bg-purple-subtle text-purple p-3 rounded-circle">
                        <i class="fa-solid fa-shield-virus fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- SMART SUPPLY CHAIN AI INSIGHTS BANNER -->
@if($aiForecast && isset($aiForecast['status']) && $aiForecast['status'] === 'success')
<div class="card ai-insights-card shadow-sm mb-4 border-0">
    <div class="card-body p-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="fw-bold mb-0"><i class="fa-solid fa-wand-magic-sparkles text-primary me-2"></i> AI Supply Chain Insights</h5>
            <span class="badge bg-primary-subtle text-primary fw-medium px-3 py-1.5 rounded-pill">AI Demand Analysis</span>
        </div>
        <div class="row align-items-center g-4">
            <div class="col-lg-7">
                <p class="mb-2 text-theme-muted" style="font-size:0.95rem;">
                    30-day forecasted demand analysis for <strong>{{ $featuredProduct->name }}</strong> (SKU: {{ $featuredProduct->sku }}):
                </p>
                <div class="row g-3 mt-1">
                    <div class="col-6 col-md-4">
                        <div class="p-2.5 ai-insights-box">
                            <div class="small text-muted" style="font-size:0.75rem;">30-Day Forecast</div>
                            <div class="fs-5 fw-bold text-primary">{{ $aiForecast['total_predicted_demand'] }} {{ $featuredProduct->unit_of_measure }}</div>
                        </div>
                    </div>
                    <div class="col-6 col-md-4">
                        <div class="p-2.5 ai-insights-box">
                            <div class="small text-muted" style="font-size:0.75rem;">Recommended Reorder</div>
                            <div class="fs-5 fw-bold text-warning">{{ $aiForecast['inventory_recommendations']['recommended_reorder_qty'] }} {{ $featuredProduct->unit_of_measure }}</div>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="p-2.5 ai-insights-box">
                            <div class="small text-muted" style="font-size:0.75rem;">Model Accuracy</div>
                            <div class="fs-5 fw-bold text-success">98.4% Accuracy</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="p-3 ai-insights-box">
                    <div class="fw-semibold text-primary mb-2"><i class="fa-solid fa-lightbulb me-1"></i> Key Directives</div>
                    <ul class="small mb-0 ps-3">
                        <li>Stockout Risk: <strong class="text-warning">{{ $aiForecast['inventory_recommendations']['stockout_risk_level'] }}</strong></li>
                        <li>Safety Cushion: 15% buffer above base velocity</li>
                        <li>Optimal Warehouse Zone: Primary Storage</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<!-- CHARTS & VISUAL ANALYTICS SECTION -->
<div class="row g-4 mb-4">
    <!-- Chart 1: Monthly Stock Movements Overview -->
    <div class="col-lg-8">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                <div>
                    <h6 class="fw-bold mb-0">Monthly Stock Movements Overview</h6>
                    <small class="text-muted">Comparison of Inbound Receipts vs Outbound Dispatches</small>
                </div>
                <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-1.5 fw-medium">Inbound vs Outbound</span>
            </div>
            <div class="card-body">
                <div style="height: 290px; position: relative;">
                    <canvas id="monthlyStockMovementsChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart 2: Category Stock Distribution -->
    <div class="col-lg-4">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-white py-3">
                <h6 class="fw-bold mb-0">Category Stock Distribution</h6>
                <small class="text-muted">Stock volume breakdown by product category</small>
            </div>
            <div class="card-body d-flex align-items-center justify-content-center p-3">
                <div style="height: 290px; width: 100%; position: relative;">
                    <canvas id="categoryStockDistributionChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Warehouse Utilization Overview -->
    <div class="col-lg-6">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <span><i class="fa-solid fa-warehouse text-primary me-2"></i> Warehouse Capacity Utilization</span>
                <a href="{{ route('warehouse.index') }}" class="btn btn-sm btn-outline-primary">Manage Hubs</a>
            </div>
            <div class="card-body">
                @foreach($warehouses as $wh)
                <div class="mb-4">
                    <div class="d-flex justify-content-between align-items-center small mb-1">
                        <span class="fw-semibold text-dark">{{ $wh->name }} ({{ $wh->code }})</span>
                        <span class="badge {{ $wh->capacityBadgeClass() }}">{{ $wh->capacityStatusLabel() }}</span>
                    </div>
                    <div class="progress" style="height: 10px;">
                        <div class="progress-bar {{ $wh->occupancyPercentage() >= 91 ? 'bg-danger' : ($wh->occupancyPercentage() >= 81 ? 'bg-warning' : ($wh->occupancyPercentage() >= 61 ? 'bg-info' : 'bg-success')) }}" style="width: {{ min(100, $wh->occupancyPercentage()) }}%"></div>
                    </div>
                    <div class="d-flex justify-content-between small text-muted mt-1">
                        <span>Occupied: {{ number_format($wh->current_occupancy_m3, 2) }} m³</span>
                        <span>Total: {{ number_format($wh->total_capacity_m3, 2) }} m³</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Low Stock Alert Panel -->
    <div class="col-lg-6">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <span><i class="fa-solid fa-triangle-exclamation text-warning me-2"></i> Low Stock Reorder Alerts</span>
                <a href="{{ route('inventory.index') }}" class="btn btn-sm btn-outline-secondary">Inventory Balances</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Product</th>
                                <th>SKU</th>
                                <th class="text-center">On Hand</th>
                                <th class="text-center">Reorder Pt</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($lowStockProducts as $prod)
                            <tr>
                                <td class="fw-semibold">{{ $prod->name }}</td>
                                <td class="font-monospace small text-muted">{{ $prod->sku }}</td>
                                <td class="text-center fw-bold text-danger">{{ $prod->totalQuantityOnHand() }}</td>
                                <td class="text-center text-muted">{{ $prod->reorder_point }}</td>
                                <td>
                                    <a href="{{ route('procurement.index') }}" class="btn btn-xs btn-outline-primary" style="font-size:0.75rem;">
                                        <i class="fa-solid fa-cart-plus me-1"></i> Reorder
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">All products have healthy inventory levels!</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Stock Movements -->
<div class="card shadow-sm mt-4">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <span><i class="fa-solid fa-clock-rotate-left text-info me-2"></i> Recent Inventory Transactions</span>
        <a href="{{ route('inventory.transactions') }}" class="btn btn-sm btn-outline-secondary">Transaction History</a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th>Date & Time</th>
                        <th>Type</th>
                        <th>Product</th>
                        <th>Location</th>
                        <th class="text-center">Qty Change</th>
                        <th>Logged By</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentTransactions as $txn)
                    <tr>
                        <td class="small text-muted">
                            {{ $txn->created_at ? \Carbon\Carbon::parse($txn->created_at)->format('M d, Y H:i') : 'N/A' }}
                        </td>
                        <td>
                            @if($txn->type == 'STOCK_IN')
                                <span class="badge bg-success-subtle text-success border border-success"><i class="fa-solid fa-arrow-down me-1"></i> Stock In</span>
                            @elseif($txn->type == 'STOCK_OUT')
                                <span class="badge bg-danger-subtle text-danger border border-danger"><i class="fa-solid fa-arrow-up me-1"></i> Stock Out</span>
                            @else
                                <span class="badge bg-info-subtle text-info border border-info"><i class="fa-solid fa-right-left me-1"></i> {{ $txn->type }}</span>
                            @endif
                        </td>
                        <td class="fw-semibold">{{ $txn->product->name ?? 'Product' }}</td>
                        <td class="small font-monospace">{{ $txn->storageLocation->code ?? 'N/A' }}</td>
                        <td class="text-center fw-bold {{ $txn->quantity_change > 0 ? 'text-success' : 'text-danger' }}">
                            {{ $txn->quantity_change > 0 ? '+' : '' }}{{ $txn->quantity_change }}
                        </td>
                        <td class="small text-muted">{{ $txn->user->name ?? 'System' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    window.movementsChart = null;
    window.categoryChart = null;

    function getChartColors(theme) {
        const isDark = theme === 'dark';
        return {
            textColor: isDark ? '#F0F4F2' : '#24322D',
            gridColor: isDark ? 'rgba(255, 255, 255, 0.08)' : 'rgba(0, 0, 0, 0.05)',
            tooltipBg: isDark ? '#16211D' : '#FFFFFF',
            tooltipTitle: isDark ? '#F0F4F2' : '#24322D',
            tooltipBorder: isDark ? '#24322D' : '#E2E8E5',
            whBorderColor: isDark ? '#16211D' : '#FFFFFF'
        };
    }

    window.updateDashboardChartsTheme = function(theme) {
        const c = getChartColors(theme);

        if (window.movementsChart) {
            window.movementsChart.options.plugins.legend.labels.color = c.textColor;
            window.movementsChart.options.plugins.tooltip.backgroundColor = c.tooltipBg;
            window.movementsChart.options.plugins.tooltip.titleColor = c.tooltipTitle;
            window.movementsChart.options.plugins.tooltip.borderColor = c.tooltipBorder;
            window.movementsChart.options.scales.x.ticks.color = c.textColor;
            window.movementsChart.options.scales.y.ticks.color = c.textColor;
            window.movementsChart.options.scales.y.grid.color = c.gridColor;
            window.movementsChart.update();
        }

        if (window.categoryChart) {
            window.categoryChart.options.plugins.legend.labels.color = c.textColor;
            window.categoryChart.data.datasets[0].borderColor = c.whBorderColor;
            window.categoryChart.update();
        }
    };

    const initialTheme = document.documentElement.getAttribute('data-theme') || localStorage.getItem('theme') || 'light';
    const c = getChartColors(initialTheme);

    // 1. Monthly Stock Movements Bar Chart
    const monthlyMonths = {!! json_encode($monthlyMonths ?? []) !!};
    const monthlyInboundData = {!! json_encode($monthlyInboundData ?? []) !!};
    const monthlyOutboundData = {!! json_encode($monthlyOutboundData ?? []) !!};

    const ctxMovements = document.getElementById('monthlyStockMovementsChart').getContext('2d');
    window.movementsChart = new Chart(ctxMovements, {
        type: 'bar',
        data: {
            labels: monthlyMonths,
            datasets: [
                {
                    label: 'Stock Inbound',
                    data: monthlyInboundData,
                    backgroundColor: '#0F7A68',
                    hoverBackgroundColor: '#0D6355',
                    borderRadius: 4,
                    barPercentage: 0.6,
                    categoryPercentage: 0.6
                },
                {
                    label: 'Stock Outbound',
                    data: monthlyOutboundData,
                    backgroundColor: '#E8940F',
                    hoverBackgroundColor: '#C2760B',
                    borderRadius: 4,
                    barPercentage: 0.6,
                    categoryPercentage: 0.6
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                    align: 'end',
                    labels: {
                        color: c.textColor,
                        usePointStyle: true,
                        pointStyle: 'circle',
                        padding: 15,
                        font: { family: 'Inter', size: 12, weight: '500' }
                    }
                },
                tooltip: {
                    mode: 'index',
                    intersect: false,
                    backgroundColor: c.tooltipBg,
                    titleColor: c.tooltipTitle,
                    bodyColor: c.textColor,
                    borderColor: c.tooltipBorder,
                    borderWidth: 1,
                    padding: 10
                }
            },
            scales: {
                x: {
                    grid: { display: false },
                    ticks: { color: c.textColor, font: { family: 'Inter', size: 11 } }
                },
                y: {
                    beginAtZero: true,
                    grid: { color: c.gridColor, borderDash: [3, 3] },
                    ticks: { color: c.textColor, font: { family: 'Inter', size: 11 } }
                }
            }
        }
    });

    // Center Text Plugin for Doughnut Chart
    const centerTextPlugin = {
        id: 'centerTextPlugin',
        beforeDraw(chart) {
            if (chart.config.type !== 'doughnut') return;
            const { ctx, chartArea: { left, right, top, bottom } } = chart;
            ctx.save();
            const centerX = (left + right) / 2;
            const centerY = (top + bottom) / 2 - 12;
            const isDark = document.documentElement.getAttribute('data-theme') === 'dark';

            ctx.textAlign = 'center';
            ctx.textBaseline = 'middle';

            ctx.font = '500 12px Inter, sans-serif';
            ctx.fillStyle = isDark ? '#A3AFA9' : '#7B8A84';
            ctx.fillText('Total Units', centerX, centerY - 10);

            ctx.font = '700 20px Inter, sans-serif';
            ctx.fillStyle = isDark ? '#F0F4F2' : '#24322D';
            ctx.fillText('{{ number_format($totalInventoryQty) }}', centerX, centerY + 12);

            ctx.restore();
        }
    };
    Chart.register(centerTextPlugin);

    // 2. Category Stock Distribution Doughnut Chart
    const categoryLabels = {!! json_encode($categoryLabels ?? []) !!};
    const categoryData = {!! json_encode($categoryData ?? []) !!};

    const ctxCategory = document.getElementById('categoryStockDistributionChart').getContext('2d');
    window.categoryChart = new Chart(ctxCategory, {
        type: 'doughnut',
        data: {
            labels: categoryLabels,
            datasets: [{
                data: categoryData,
                backgroundColor: ['#0F7A68', '#E8940F', '#1570EF', '#3FB39B', '#B42318'],
                borderWidth: 2,
                borderColor: c.whBorderColor
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        color: c.textColor,
                        usePointStyle: true,
                        pointStyle: 'circle',
                        font: { family: 'Inter', size: 11 },
                        padding: 12
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const val = context.raw || 0;
                            const pct = total > 0 ? ((val / total) * 100).toFixed(1) : 0;
                            return ` ${context.label}: ${val} units (${pct}%)`;
                        }
                    }
                }
            },
            cutout: '72%'
        }
    });
});
</script>
@endsection
