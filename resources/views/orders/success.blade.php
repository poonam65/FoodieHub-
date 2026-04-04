@extends('layouts.app')
@section('title', 'Order Placed!')

@section('content')
<div class="container py-5 text-center">
    <div class="card mx-auto p-5" style="max-width:600px">
        <div class="mb-4">
            <div style="width:100px;height:100px;background:#d4edda;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto">
                <i class="fas fa-check fa-3x text-success"></i>
            </div>
        </div>
        <h2 style="font-family:'Playfair Display',serif" class="text-success">Order Placed!</h2>
        <p class="text-muted mt-2">Your order <strong>{{ $order->order_number }}</strong> has been received.</p>
        <div class="card bg-light p-3 mt-3 text-start">
            <h6>Order Details</h6>
            @foreach($order->items as $item)
            <div class="d-flex justify-content-between">
                <span>{{ $item->menuItem->name }} × {{ $item->quantity }}</span>
                <span>₹{{ number_format($item->subtotal, 2) }}</span>
            </div>
            @endforeach
            <hr>
            <div class="d-flex justify-content-between fw-bold">
                <span>Total Paid</span>
                <span class="text-danger">₹{{ number_format($order->total, 2) }}</span>
            </div>
        </div>
        <div class="mt-4 d-flex gap-3 justify-content-center">
            <a href="{{ route('home') }}" class="btn btn-primary rounded-pill px-4">Back to Home</a>
            @auth
            <a href="{{ route('orders.history') }}" class="btn btn-outline-secondary rounded-pill px-4">My Orders</a>
            @endauth
        </div>
    </div>
</div>
@endsection