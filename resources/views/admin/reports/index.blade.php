@extends('layouts.admin')

@section('title', 'Reports')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
@endpush

@section('content')
{{-- Date Filter --}}
<div class="card card-outline card-primary">
    <div class="card-header"><h3 class="card-title"><i class="fas fa-filter mr-1"></i> Date Range Filter</h3></div>
    <div class="card-body">
        <form method="GET" action="{{ route('admin.reports.index') }}" class="form-inline">
            <div class="input-group mr-2">
                <input type="text" name="from" class="form-control flatpickr" value="{{ $from }}" placeholder="From">
                <div class="input-group-append"><span class="input-group-text"><i class="fas fa-calendar"></i></span></div>
            </div>
            <div class="input-group mr-2">
                <input type="text" name="to" class="form-control flatpickr" value="{{ $to }}" placeholder="To">
                <div class="input-group-append"><span class="input-group-text"><i class="fas fa-calendar"></i></span></div>
            </div>
            <input type="hidden" name="year" value="{{ $year }}">
            <button type="submit" class="btn btn-primary mr-2">Apply</button>
            <a href="{{ route('admin.reports.export-csv', request()->query()) }}" class="btn btn-success">
                <i class="fas fa-file-csv mr-1"></i>Export CSV
            </a>
        </form>
    </div>
</div>

{{-- Summary Cards --}}
<div class="row">
    <div class="col-md-4">
        <div class="info-box bg-info">
            <span class="info-box-icon"><i class="fas fa-shopping-cart"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Total Orders</span>
                <span class="info-box-number">{{ number_format($summary['total_orders']) }}</span>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="info-box bg-success">
            <span class="info-box-icon"><i class="fas fa-dollar-sign"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Revenue</span>
                <span class="info-box-number">${{ number_format($summary['revenue'], 2) }}</span>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="info-box bg-warning">
            <span class="info-box-icon"><i class="fas fa-chart-line"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Avg Order Value</span>
                <span class="info-box-number">${{ number_format($summary['avg_order_value'], 2) }}</span>
            </div>
        </div>
    </div>
</div>

<div class="row">
    {{-- Revenue by Month Chart --}}
    <div class="col-md-8">
        <div class="card">
            <div class="card-header"><h3 class="card-title">Revenue by Month ({{ $year }})</h3></div>
            <div class="card-body"><canvas id="revenueChart" height="120"></canvas></div>
        </div>
    </div>

    {{-- Orders by Status --}}
    <div class="col-md-4">
        <div class="card">
            <div class="card-header"><h3 class="card-title">Orders by Status</h3></div>
            <div class="card-body"><canvas id="statusChart" height="200"></canvas></div>
        </div>
    </div>
</div>

<div class="row">
    {{-- Top Products --}}
    <div class="col-md-7">
        <div class="card">
            <div class="card-header"><h3 class="card-title">Top Selling Products</h3></div>
            <div class="card-body p-0">
                <table class="table table-striped">
                    <thead><tr><th>#</th><th>Product</th><th>Units Sold</th><th>Revenue</th></tr></thead>
                    <tbody>
                        @forelse($topProducts as $i => $product)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ $product->name }}</td>
                            <td>{{ number_format($product->total_sold) }}</td>
                            <td>${{ number_format($product->revenue, 2) }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-center">No sales data in this period.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Low Stock --}}
    <div class="col-md-5">
        <div class="card card-warning">
            <div class="card-header"><h3 class="card-title"><i class="fas fa-exclamation-triangle mr-1"></i>Low Stock Products</h3></div>
            <div class="card-body p-0">
                <table class="table">
                    <thead><tr><th>Product</th><th>Stock</th></tr></thead>
                    <tbody>
                        @forelse($lowStock as $product)
                        <tr class="{{ $product->stock == 0 ? 'table-danger' : 'table-warning' }}">
                            <td>{{ $product->name }}</td>
                            <td><span class="badge badge-{{ $product->stock == 0 ? 'danger' : 'warning' }}">{{ $product->stock }}</span></td>
                        </tr>
                        @empty
                        <tr><td colspan="2" class="text-center text-success"><i class="fas fa-check-circle"></i> All products in stock</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    document.querySelectorAll('.flatpickr').forEach(el => flatpickr(el, {}));

    // Revenue chart
    const months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
    const revenueData = @json($revenueByMonth);
    const revenueValues = months.map((_, i) => revenueData[i + 1] ?? 0);

    new Chart(document.getElementById('revenueChart'), {
        type: 'bar',
        data: {
            labels: months,
            datasets: [{ label: 'Revenue ($)', data: revenueValues, backgroundColor: 'rgba(60,141,188,0.7)', borderColor: '#3c8dbc', borderWidth: 1 }]
        },
        options: { responsive: true, scales: { y: { beginAtZero: true } } }
    });

    // Status pie chart
    const statusData = @json($ordersByStatus);
    new Chart(document.getElementById('statusChart'), {
        type: 'doughnut',
        data: {
            labels: Object.keys(statusData).map(s => s.charAt(0).toUpperCase() + s.slice(1)),
            datasets: [{ data: Object.values(statusData), backgroundColor: ['#f39c12','#3c8dbc','#00a65a','#605ca8','#dd4b39'] }]
        },
        options: { responsive: true }
    });
</script>
@endpush
