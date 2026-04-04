@extends('layouts.app')
@section('title', 'Order Details')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 style="font-family:'Playfair Display',serif">Order Details</h2>
                <a href="{{ route('orders.history') }}" class="btn btn-outline-secondary btn-sm">
                    Back to Orders
                </a>
            </div>

            <div class="card mb-4 p-4">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-muted">Order Number</h6>
                        <p><strong>{{ $order->order_number }}</strong></p>
                        <h6 class="text-muted">Order Date</h6>
                        <p>{{ $order->created_at->format('d M Y, h:i A') }}</p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted">Status</h6>
                        <span class="badge bg-{{ $order->status_badge }} fs-6">
                            {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                        </span>
                        <h6 class="text-muted mt-3">Delivery Address</h6>
                        <p>{{ $order->delivery_address }}</p>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Order Items</h5>
                </div>
                <div class="card-body p-0">
                    <table class="table mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Item</th>
                                <th>Price</th>
                                <th>Qty</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $item)
                            <tr>
                                <td>{{ $item->menuItem->name ?? 'Item deleted' }}</td>
                                <td>Rs.{{ number_format($item->price, 2) }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>Rs.{{ number_format($item->subtotal, 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    <div class="d-flex justify-content-between">
                        <span>Subtotal</span>
                        <span>Rs.{{ number_format($order->subtotal, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>Delivery Fee</span>
                        <span>Rs.{{ number_format($order->delivery_fee, 2) }}</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between">
                        <strong>Total</strong>
                        <strong class="text-danger">Rs.{{ number_format($order->total, 2) }}</strong>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection