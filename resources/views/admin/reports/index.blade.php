@extends('layouts.admin')
@section('title', 'Reports & Analytics')

@section('content')

<!-- Period Filter -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4>📊 Reports & Analytics</h4>
    <div class="d-flex gap-2">
        @foreach(['today' => 'Today', 'week' => 'This Week', 'month' => 'This Month', 'year' => 'This Year'] as $key => $label)
            <a href="{{ route('admin.reports.index', ['period' => $key]) }}"
                class="btn btn-sm rounded-pill {{ $period === $key ? 'btn-danger' : 'btn-outline-secondary' }}">
                {{ $label }}
            </a>
        @endforeach
    </div>
</div>

<!-- ✅ Overview Cards -->
<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card p-4 text-center h-100">
            <i class="fas fa-shopping-bag fa-2x text-primary mb-2"></i>
            <h3 class="mb-0">{{ $overview['total_orders'] }}</h3>
            <p class="text-muted mb-0 small">Total Orders</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card p-4 text-center h-100">
            <i class="fas fa-rupee-sign fa-2x text-success mb-2"></i>
            <h3 class="mb-0">Rs.{{ number_format($overview['total_revenue'], 0) }}</h3>
            <p class="text-muted mb-0 small">Total Revenue</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card p-4 text-center h-100">
            <i class="fas fa-check-circle fa-2x text-success mb-2"></i>
            <h3 class="mb-0">{{ $overview['delivered_orders'] }}</h3>
            <p class="text-muted mb-0 small">Delivered Orders</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card p-4 text-center h-100">
            <i class="fas fa-chart-line fa-2x text-info mb-2"></i>
            <h3 class="mb-0">Rs.{{ number_format($overview['avg_order_value'], 0) }}</h3>
            <p class="text-muted mb-0 small">Avg Order Value</p>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card p-4 text-center h-100">
            <i class="fas fa-clock fa-2x text-warning mb-2"></i>
            <h3 class="mb-0">{{ $overview['pending_orders'] }}</h3>
            <p class="text-muted mb-0 small">Pending Orders</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card p-4 text-center h-100">
            <i class="fas fa-times-circle fa-2x text-danger mb-2"></i>
            <h3 class="mb-0">{{ $overview['cancelled_orders'] }}</h3>
            <p class="text-muted mb-0 small">Cancelled Orders</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card p-4 text-center h-100">
            <i class="fas fa-users fa-2x text-purple mb-2"></i>
            <h3 class="mb-0">{{ $overview['total_customers'] }}</h3>
            <p class="text-muted mb-0 small">Total Customers</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card p-4 text-center h-100">
            <i class="fas fa-user-plus fa-2x text-info mb-2"></i>
            <h3 class="mb-0">{{ $overview['new_customers'] }}</h3>
            <p class="text-muted mb-0 small">New Customers</p>
        </div>
    </div>
</div>

<!-- ✅ Daily Sales Chart -->
<div class="card mb-4 p-4">
    <h5 class="mb-4">📈 Last 30 Days — Sales Chart</h5>
    <canvas id="salesChart" height="100"></canvas>
</div>

<div class="row g-4 mb-4">
    <!-- ✅ Best Selling Items -->
    <div class="col-lg-7">
        <div class="card h-100">
            <div class="card-header">
                <h5 class="mb-0">🏆 Best Selling Items</h5>
            </div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Item</th>
                            <th>Category</th>
                            <th>Qty Sold</th>
                            <th>Revenue</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bestSellingItems as $index => $item)
                        <tr>
                            <td>
                                @if($index === 0) 🥇
                                @elseif($index === 1) 🥈
                                @elseif($index === 2) 🥉
                                @else {{ $index + 1 }}
                                @endif
                            </td>
                            <td>
                                <strong>{{ $item->menuItem->name ?? 'N/A' }}</strong>
                            </td>
                            <td>
                                <span class="badge bg-warning text-dark">
                                    {{ $item->menuItem->category->name ?? 'N/A' }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-primary">
                                    {{ $item->total_quantity }} units
                                </span>
                            </td>
                            <td>
                                <strong class="text-success">
                                    Rs.{{ number_format($item->total_revenue, 0) }}
                                </strong>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">
                                Koi data nahi hai
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- ✅ Order Status Distribution -->
    <div class="col-lg-5">
        <div class="card h-100 p-4">
            <h5 class="mb-4">🎯 Order Status Distribution</h5>
            <canvas id="statusChart"></canvas>
            <div class="mt-3">
                @foreach($statusDistribution as $status)
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span>{{ ucfirst(str_replace('_', ' ', $status->status)) }}</span>
                    <span class="badge bg-secondary">{{ $status->count }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <!-- ✅ Category Sales -->
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header">
                <h5 class="mb-0">🍽️ Category wise Sales</h5>
            </div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Category</th>
                            <th>Revenue</th>
                            <th>Share</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $totalRevenue = $categorySales->sum('revenue'); @endphp
                        @forelse($categorySales as $cat)
                        @if($cat->revenue > 0)
                        <tr>
                            <td><strong>{{ $cat->name }}</strong></td>
                            <td>
                                <strong class="text-success">
                                    Rs.{{ number_format($cat->revenue, 0) }}
                                </strong>
                            </td>
                            <td style="min-width:150px">
                                @php
                                    $percent = $totalRevenue > 0
                                        ? round(($cat->revenue / $totalRevenue) * 100)
                                        : 0;
                                @endphp
                                <div class="progress" style="height:8px">
                                    <div class="progress-bar bg-danger"
                                        style="width:{{ $percent }}%"></div>
                                </div>
                                <small class="text-muted">{{ $percent }}%</small>
                            </td>
                        </tr>
                        @endif
                        @empty
                        <tr>
                            <td colspan="3" class="text-center text-muted py-4">
                                Koi data nahi
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- ✅ Top Customers -->
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header">
                <h5 class="mb-0">👑 Top Customers</h5>
            </div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Customer</th>
                            <th>Orders</th>
                            <th>Spent</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($topCustomers as $index => $customer)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                <strong>{{ $customer->customer_name }}</strong>
                                <br>
                                <small class="text-muted">{{ $customer->customer_email }}</small>
                            </td>
                            <td>
                                <span class="badge bg-primary">
                                    {{ $customer->total_orders }}
                                </span>
                            </td>
                            <td>
                                <strong class="text-danger">
                                    Rs.{{ number_format($customer->total_spent, 0) }}
                                </strong>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-4">
                                Koi data nahi
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// ✅ Daily Sales Chart
const dailyLabels = @json($dailySales->pluck('date'));
const dailyRevenue = @json($dailySales->pluck('revenue'));
const dailyOrders = @json($dailySales->pluck('orders'));

const salesCtx = document.getElementById('salesChart').getContext('2d');
new Chart(salesCtx, {
    type: 'line',
    data: {
        labels: dailyLabels,
        datasets: [
            {
                label: 'Revenue (Rs.)',
                data: dailyRevenue,
                borderColor: '#e63946',
                backgroundColor: 'rgba(230,57,70,0.1)',
                tension: 0.4,
                fill: true,
                yAxisID: 'y',
            },
            {
                label: 'Orders',
                data: dailyOrders,
                borderColor: '#f4a261',
                backgroundColor: 'rgba(244,162,97,0.1)',
                tension: 0.4,
                fill: true,
                yAxisID: 'y1',
            }
        ]
    },
    options: {
        responsive: true,
        interaction: { mode: 'index', intersect: false },
        scales: {
            y: {
                type: 'linear',
                display: true,
                position: 'left',
                title: { display: true, text: 'Revenue (Rs.)' }
            },
            y1: {
                type: 'linear',
                display: true,
                position: 'right',
                title: { display: true, text: 'Orders' },
                grid: { drawOnChartArea: false }
            }
        }
    }
});

// ✅ Status Distribution Chart
const statusLabels = @json($statusDistribution->pluck('status')->map(fn($s) => ucfirst(str_replace('_', ' ', $s))));
const statusData   = @json($statusDistribution->pluck('count'));

const statusCtx = document.getElementById('statusChart').getContext('2d');
new Chart(statusCtx, {
    type: 'doughnut',
    data: {
        labels: statusLabels,
        datasets: [{
            data: statusData,
            backgroundColor: [
                '#ffc107', '#0dcaf0', '#0d6efd',
                '#6c757d', '#198754', '#dc3545'
            ],
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { position: 'bottom' }
        }
    }
});
</script>
@endpush