@extends('layouts.app')

@section('title', 'AI Analytics & Executive Reports')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">AI Analytics & Executive System Reports</h3>
        <p class="text-muted small mb-0">Model evaluation metrics, historical vs predicted demand analysis, and system audit logs.</p>
    </div>
    <span class="ai-badge fs-6"><i class="fa-solid fa-brain me-1"></i> Scikit-Learn ML Analytics</span>
</div>

<!-- AI Model Evaluation Metrics Banner -->
@if($aiReport && isset($aiReport['model_info']))
<div class="card shadow-sm mb-4">
    <div class="card-header bg-white py-3">
        <h5 class="fw-bold mb-0"><i class="fa-solid fa-chart-line text-purple me-2"></i> Scikit-Learn Demand Forecasting Model Evaluation</h5>
    </div>
    <div class="card-body">
        <div class="row align-items-center">
            <div class="col-md-4 text-center border-end">
                <div class="text-muted small fw-semibold text-uppercase">Active ML Algorithm</div>
                <div class="fw-bold fs-5 text-purple mt-1">{{ $aiReport['model_info']['algorithm'] }}</div>
                <div class="badge bg-secondary mt-1">Evaluated via 80/20 Train-Test Split</div>
            </div>
            <div class="col-md-8">
                <div class="row text-center g-3">
                    <div class="col-4">
                        <div class="p-3 bg-light rounded border">
                            <div class="text-muted small">Mean Absolute Error (MAE)</div>
                            <div class="display-6 fw-bold text-dark">{{ $aiReport['model_info']['evaluation_metrics']['mae'] }}</div>
                            <div class="text-muted small" style="font-size:0.7rem;">Average units deviation</div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="p-3 bg-light rounded border">
                            <div class="text-muted small">Root Mean Sq Error (RMSE)</div>
                            <div class="display-6 fw-bold text-primary">{{ $aiReport['model_info']['evaluation_metrics']['rmse'] }}</div>
                            <div class="text-muted small" style="font-size:0.7rem;">Penalizes large variance</div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="p-3 bg-light rounded border">
                            <div class="text-muted small">R² Fit Score</div>
                            <div class="display-6 fw-bold text-success">{{ $aiReport['model_info']['evaluation_metrics']['r2_score'] }}</div>
                            <div class="text-muted small" style="font-size:0.7rem;">1.0 = Perfect Variance Fit</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<!-- SUPPLIER ANALYTICS CHART -->
<div class="card shadow-sm mb-4">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h5 class="fw-bold mb-0"><i class="fa-solid fa-chart-column text-primary me-2"></i> Supplier Rating Score & Purchase Order Volume Comparison</h5>
        <span class="badge bg-secondary">Multi-Axis Analytics</span>
    </div>
    <div class="card-body">
        <div style="height: 280px; position: relative;">
            <canvas id="supplierAnalyticsChart"></canvas>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Supplier Performance Matrix -->
    <div class="col-lg-6">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-white py-3">
                <h5 class="fw-bold mb-0"><i class="fa-solid fa-star text-warning me-2"></i> Supplier Performance Matrix</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Supplier</th>
                                <th class="text-center">Orders</th>
                                <th class="text-center">Rating Score</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($suppliers as $sup)
                            <tr>
                                <td class="fw-semibold">{{ $sup->company_name }} <div class="text-muted small">{{ $sup->code }}</div></td>
                                <td class="text-center fw-bold">{{ $sup->purchase_orders_count }} POs</td>
                                <td class="text-center">
                                    <span class="badge bg-success-subtle text-success fs-6">★ {{ number_format($sup->rating_score, 2) }}</span>
                                </td>
                                <td><span class="badge bg-success">Accredited</span></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Audit Logs Trail -->
    <div class="col-lg-6">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-white py-3">
                <h5 class="fw-bold mb-0"><i class="fa-solid fa-shield-halved text-info me-2"></i> System Audit Log Activity</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Time</th>
                                <th>User</th>
                                <th>Action</th>
                                <th>Module</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentLogs as $log)
                            <tr>
                                <td class="small text-muted">{{ $log->created_at ? \Carbon\Carbon::parse($log->created_at)->format('H:i:s M d') : 'N/A' }}</td>
                                <td class="fw-semibold small">{{ $log->user->name ?? 'System' }}</td>
                                <td><span class="badge bg-dark">{{ $log->action }}</span></td>
                                <td class="small text-muted">{{ $log->module }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    @if(isset($suppliers) && count($suppliers) > 0)
        const supplierNames = {!! json_encode($suppliers->pluck('company_name')->toArray()) !!};
        const poCounts = {!! json_encode($suppliers->pluck('purchase_orders_count')->toArray()) !!};
        const ratingScores = {!! json_encode($suppliers->pluck('rating_score')->toArray()) !!};

        const ctxSupplier = document.getElementById('supplierAnalyticsChart').getContext('2d');
        
        function getThemeColors(theme) {
            const isDark = theme === 'dark';
            return {
                textColor: isDark ? '#F8FAFC' : '#0F172A',
                gridColor: isDark ? 'rgba(255, 255, 255, 0.1)' : 'rgba(0, 0, 0, 0.08)'
            };
        }

        const initialTheme = document.documentElement.getAttribute('data-theme') || 'light';
        const colors = getThemeColors(initialTheme);

        window.supplierChart = new Chart(ctxSupplier, {
            type: 'bar',
            data: {
                labels: supplierNames,
                datasets: [
                    {
                        type: 'bar',
                        label: 'Total Purchase Orders',
                        data: poCounts,
                        backgroundColor: 'rgba(37, 99, 235, 0.75)',
                        borderColor: '#2563eb',
                        borderWidth: 1,
                        yAxisID: 'y'
                    },
                    {
                        type: 'line',
                        label: 'Rating Score (Out of 5.0)',
                        data: ratingScores,
                        borderColor: '#f59e0b',
                        backgroundColor: '#f59e0b',
                        borderWidth: 3,
                        pointRadius: 5,
                        pointHoverRadius: 7,
                        tension: 0.2,
                        yAxisID: 'y1'
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        labels: { color: colors.textColor, font: { family: 'Inter', weight: '600' } }
                    }
                },
                scales: {
                    x: {
                        grid: { color: colors.gridColor },
                        ticks: { color: colors.textColor }
                    },
                    y: {
                        type: 'linear',
                        display: true,
                        position: 'left',
                        beginAtZero: true,
                        grid: { color: colors.gridColor },
                        ticks: { color: colors.textColor, stepSize: 1 },
                        title: { display: true, text: 'Purchase Orders Count', color: colors.textColor }
                    },
                    y1: {
                        type: 'linear',
                        display: true,
                        position: 'right',
                        min: 0,
                        max: 5,
                        grid: { drawOnChartArea: false },
                        ticks: { color: colors.textColor },
                        title: { display: true, text: 'Rating Score', color: colors.textColor }
                    }
                }
            }
        });

        window.updateReportsChartsTheme = function(theme) {
            if (!window.supplierChart) return;
            const c = getThemeColors(theme);
            
            window.supplierChart.options.plugins.legend.labels.color = c.textColor;
            window.supplierChart.options.scales.x.grid.color = c.gridColor;
            window.supplierChart.options.scales.x.ticks.color = c.textColor;
            window.supplierChart.options.scales.y.grid.color = c.gridColor;
            window.supplierChart.options.scales.y.ticks.color = c.textColor;
            window.supplierChart.options.scales.y.title.color = c.textColor;
            window.supplierChart.options.scales.y1.ticks.color = c.textColor;
            window.supplierChart.options.scales.y1.title.color = c.textColor;
            
            window.supplierChart.update();
        };
    @endif
});
</script>
@endsection
