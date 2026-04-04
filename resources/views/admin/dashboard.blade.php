@extends('layouts.admin')
@section('title', 'Admin Dashboard')
@section('content')
<h4 class="mb-4">Dashboard</h4>
<div class="row g-4 mb-5">
    <div class="col-md-3">
        <div class="card p-4 text-center">
            <i class="fas fa-shopping-bag fa-2x text-primary mb-2"></i>
            <h3>{{ $stats['total_orders'] }}</h3>
            <p class="text-muted mb-0">Total Orders</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card p-4 text-center">
            <i class="fas fa-clock fa-2x text-warning mb-2"></i>
            <h3>{{ $stats['pending_orders'] }}</h3>
            <p class="text-muted mb-0">Pending Orders</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card p-4 text-center">
            <i class="fas fa-rupee-sign fa-2x text-success mb-2"></i>
            <h3>Rs.{{ number_format($stats['total_revenue'], 0) }}</h3>
            <p class="text-muted mb-0">Total Revenue</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card p-4 text-center">
            <i class="fas fa-utensils fa-2x text-danger mb-2"></i>
            <h3>{{ $stats['total_items'] }}</h3>
            <p class="text-muted mb-0">Menu Items</p>
        </div>
    </div>
</div>
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Recent Orders</h5>
        <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
    </div>
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Order #</th><th>Customer</th><th>Total</th><th>Status</th><th>Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentOrders as $order)
                <tr>
                    <td><a href="{{ route('admin.orders.show', $order) }}">{{ $order->order_number }}</a></td>
                    <td>{{ $order->customer_name }}</td>
                    <td>Rs.{{ number_format($order->total, 2) }}</td>
                    <td><span class="badge bg-{{ $order->status_badge }}">{{ ucfirst(str_replace('_', ' ', $order->status)) }}</span></td>
                    <td>{{ $order->created_at->format('d M, h:i A') }}</td>
                </tr>
                @empty
                <tr><td colspan="5" class="text-center text-muted py-4">Koi order nahi hai</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection