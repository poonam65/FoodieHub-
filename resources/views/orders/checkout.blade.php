@extends('layouts.app')
@section('title', 'Checkout')

@section('content')
<div class="container py-5">
    <h2 style="font-family:'Playfair Display',serif" class="mb-4">📦 Checkout</h2>
    <div class="row g-4">
        <div class="col-lg-7">
            <div class="card p-4">
                <h5 class="mb-4">Delivery Details</h5>
                <form action="{{ route('orders.place') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Full Name *</label>
                        <input type="text" name="customer_name"
                            class="form-control @error('customer_name') is-invalid @enderror"
                            value="{{ old('customer_name', auth()->user()->name ?? '') }}" required>
                        @error('customer_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Email *</label>
                            <input type="email" name="customer_email"
                                class="form-control @error('customer_email') is-invalid @enderror"
                                value="{{ old('customer_email', auth()->user()->email ?? '') }}" required>
                            @error('customer_email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Phone *</label>
                            <input type="text" name="customer_phone"
                                class="form-control @error('customer_phone') is-invalid @enderror"
                                value="{{ old('customer_phone') }}" required>
                            @error('customer_phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Delivery Address *</label>
                        <textarea name="delivery_address" rows="3"
                            class="form-control @error('delivery_address') is-invalid @enderror"
                            required>{{ old('delivery_address') }}</textarea>
                        @error('delivery_address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Special Instructions</label>
                        <textarea name="notes" rows="2" class="form-control"
                            placeholder="Any special requests?">{{ old('notes') }}</textarea>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 py-3 rounded-pill fs-5">
                        <i class="fas fa-check-circle me-2"></i>
                        Place Order — Rs.{{ number_format($total, 2) }}
                    </button>
                </form>
            </div>
        </div>

        {{-- Order Summary --}}
        <div class="col-lg-5">
            <div class="card p-4">
                <h5 class="mb-3">Order Summary</h5>

                {{-- Cart Items --}}
                @foreach($cart as $item)
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span>
                        {{ $item['name'] }}
                        <span class="text-muted">× {{ $item['quantity'] }}</span>
                    </span>
                    <strong>Rs.{{ number_format($item['price'] * $item['quantity'], 2) }}</strong>
                </div>
                @endforeach

                <hr>

                {{-- Subtotal --}}
                <div class="d-flex justify-content-between mb-2">
                    <span>Subtotal</span>
                    <span>Rs.{{ number_format($subtotal, 2) }}</span>
                </div>

                {{-- Delivery --}}
                <div class="d-flex justify-content-between mb-2">
                    <span>Delivery Fee</span>
                    <span>Rs.{{ number_format($delivery, 2) }}</span>
                </div>

                {{-- ✅ Discount Row --}}
                @if(session('coupon'))
                    <div class="d-flex justify-content-between mb-2 text-success">
                        <span>
                            <i class="fas fa-tag me-1"></i>
                            Discount ({{ session('coupon')['code'] }})
                        </span>
                        <strong>- Rs.{{ number_format(session('coupon')['discount'], 2) }}</strong>
                    </div>
                @endif

                <hr>

                {{-- ✅ Final Total with discount --}}
                <div class="d-flex justify-content-between mb-3">
                    <strong class="fs-5">Total</strong>
                    <strong class="text-danger fs-5">
                        Rs.{{ number_format($total, 2) }}
                    </strong>
                </div>

                {{-- ✅ Saving message --}}
                @if(session('coupon'))
                    <div class="alert alert-success py-2 text-center small">
                        🎉 You are saving
                        <strong>Rs.{{ number_format(session('coupon')['discount'], 2) }}</strong>
                        on this order!
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection