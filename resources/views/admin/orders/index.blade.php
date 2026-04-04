@extends('layouts.admin')
@section('title', 'All Orders')

@section('content')

{{-- Stats --}}
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card p-3 text-center">
            <h4 class="mb-0">{{ $stats['total'] }}</h4>
            <small class="text-muted">Total Orders</small>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card p-3 text-center">
            <h4 class="mb-0 text-warning">{{ $stats['pending'] }}</h4>
            <small class="text-muted">Pending</small>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card p-3 text-center">
            <h4 class="mb-0 text-success">{{ $stats['delivered'] }}</h4>
            <small class="text-muted">Delivered</small>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card p-3 text-center">
            <h4 class="mb-0 text-danger">{{ $stats['cancelled'] }}</h4>
            <small class="text-muted">Cancelled</small>
        </div>
    </div>
</div>

{{-- Filter Buttons --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="mb-0">All Orders</h5>
    <div class="d-flex gap-2 flex-wrap">
        <a href="{{ route('admin.orders.index') }}"
            class="btn btn-sm rounded-pill {{ !request('status') ? 'btn-danger' : 'btn-outline-secondary' }}">
            All
        </a>
        @foreach(['pending'=>'warning','confirmed'=>'info','preparing'=>'primary','out_for_delivery'=>'secondary','delivered'=>'success','cancelled'=>'danger'] as $status => $color)
            <a href="{{ route('admin.orders.index', ['status' => $status]) }}"
                class="btn btn-sm rounded-pill {{ request('status') == $status ? 'btn-'.$color : 'btn-outline-'.$color }}">
                {{ ucfirst(str_replace('_', ' ', $status)) }}
            </a>
        @endforeach
    </div>
</div>

{{-- Orders Table --}}
<div class="card">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Order #</th>
                    <th>Customer</th>
                    <th>Phone</th>
                    <th>Items</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                <tr>
                    <td><strong>{{ $order->order_number }}</strong></td>
                    <td>{{ $order->customer_name }}</td>
                    <td>{{ $order->customer_phone }}</td>
                    <td>
                        <span class="badge bg-secondary">
                            {{ $order->items->count() }} items
                        </span>
                    </td>
                    <td>
                        <strong class="text-danger">
                            Rs.{{ number_format($order->total, 2) }}
                        </strong>
                    </td>
                    <td>
                        <span class="badge bg-{{ $order->status_badge }}">
                            {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                        </span>
                    </td>
                    <td>{{ $order->created_at->format('d M, h:i A') }}</td>
                    <td>
                        <a href="{{ route('admin.orders.show', $order) }}"
                            class="btn btn-sm btn-outline-primary rounded-pill me-1">
                            <i class="fas fa-eye"></i> View
                        </a>
                        <a href="{{ route('admin.orders.invoice', $order) }}"
                            class="btn btn-sm btn-outline-success rounded-pill"
                            target="_blank">
                            <i class="fas fa-print"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center text-muted py-5">
                        <i class="fas fa-shopping-bag fa-3x mb-3 d-block opacity-25"></i>
                        Koi order nahi hai
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-4">
    {{ $orders->appends(request()->query())->links() }}
</div>

@endsection