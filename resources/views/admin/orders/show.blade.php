@extends('layouts.admin')
@section('title', 'Order Details')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4>Order — {{ $order->order_number }}</h4>
    <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary rounded-pill px-4">Back</a>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card p-4 mb-4">
            <h5 class="mb-3">Order Items</h5>
            <table class="table">
                <thead class="table-light">
                    <tr><th>Item</th><th>Price</th><th>Qty</th><th>Subtotal</th></tr>
                </thead>
                <tbody>
                    @foreach($order->items as $item)
                    <tr>
                        <td>{{ $item->menuItem->name ?? 'Deleted' }}</td>
                        <td>Rs.{{ number_format($item->price, 2) }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>Rs.{{ number_format($item->subtotal, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr><td colspan="3">Subtotal</td><td>Rs.{{ number_format($order->subtotal, 2) }}</td></tr>
                    <tr><td colspan="3">Delivery Fee</td><td>Rs.{{ number_format($order->delivery_fee, 2) }}</td></tr>
                    <tr class="fw-bold text-danger"><td colspan="3">Total</td><td>Rs.{{ number_format($order->total, 2) }}</td></tr>
                </tfoot>
            </table>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card p-4 mb-4">
            <h5 class="mb-3">Customer Info</h5>
            <p><i class="fas fa-user me-2 text-danger"></i>{{ $order->customer_name }}</p>
            <p><i class="fas fa-envelope me-2 text-danger"></i>{{ $order->customer_email }}</p>
            <p><i class="fas fa-phone me-2 text-danger"></i>{{ $order->customer_phone }}</p>
            <p><i class="fas fa-map-marker-alt me-2 text-danger"></i>{{ $order->delivery_address }}</p>
            @if($order->notes)
                <p><i class="fas fa-sticky-note me-2 text-danger"></i>{{ $order->notes }}</p>
            @endif
        </div>
<!-- Back button ke paas ye add karo -->
<a href="{{ route('admin.orders.invoice', $order) }}"
    class="btn btn-outline-danger rounded-pill px-4"
    target="_blank">
    🖨️ Print Invoice
</a>
        <div class="card p-4">
            <h5 class="mb-3">Update Status</h5>
            <form action="{{ route('admin.orders.updateStatus', $order) }}" method="POST">
                @csrf @method('PATCH')
                <select name="status" class="form-select mb-3">
                    @foreach(['pending','confirmed','preparing','out_for_delivery','delivered','cancelled'] as $s)
                        <option value="{{ $s }}" {{ $order->status == $s ? 'selected' : '' }}>
                            {{ ucfirst(str_replace('_', ' ', $s)) }}
                        </option>
                    @endforeach
                </select>
                <button type="submit" class="btn btn-primary w-100 rounded-pill">Update Status</button>
            </form>
        </div>
    </div>
</div>
@endsection