@extends('layouts.app')
@section('title', 'Your Cart')

@section('content')

{{-- ✅ Always define $discount at the top before any @if block --}}
@php
    $discount   = session('coupon') ? session('coupon')['discount'] : 0;
    $finalTotal = isset($total) ? ($total + 50 - $discount) : (50 - $discount);
@endphp

<div class="container py-5">
    <h2 style="font-family:'Playfair Display',serif" class="mb-4">🛒 Your Cart</h2>

    @if(empty($cart))
        <div class="text-center py-5">
            <i class="fas fa-shopping-cart fa-4x text-muted mb-3"></i>
            <h4 class="text-muted">Your cart is empty</h4>
            <a href="{{ route('menu.index') }}" class="btn btn-primary mt-3 rounded-pill px-5">
                Browse Menu
            </a>
        </div>
    @else
        <div class="row g-4">

            {{-- ======================== --}}
            {{--       CART ITEMS         --}}
            {{-- ======================== --}}
            <div class="col-lg-8">

                @foreach($cart as $id => $item)
                <div class="card mb-3 p-3">
                    <div class="row align-items-center">

                        {{-- Image --}}
                        <div class="col-auto">
                            @if($item['image'])
                                <img src="{{ asset('storage/'.$item['image']) }}"
                                    width="80" height="80"
                                    style="object-fit:cover;border-radius:12px">
                            @else
                                <div style="width:80px;height:80px;background:#ffd7d7;border-radius:12px;
                                    display:flex;align-items:center;justify-content:center">
                                    <i class="fas fa-utensils text-danger"></i>
                                </div>
                            @endif
                        </div>

                        {{-- Name & Price --}}
                        <div class="col">
                            <h6 class="mb-1">{{ $item['name'] }}</h6>
                            <small class="text-muted">Rs.{{ number_format($item['price'], 2) }} each</small>
                        </div>

                        {{-- Quantity Controls --}}
                        <div class="col-auto">
                            <form action="{{ route('cart.update', $id) }}"
                                method="POST"
                                class="d-flex align-items-center gap-2">
                                @csrf @method('PATCH')
                                <button type="button"
                                    class="btn btn-outline-secondary btn-sm qty-minus"
                                    data-id="{{ $id }}">-</button>
                                <input type="number"
                                    name="quantity"
                                    id="qty-{{ $id }}"
                                    value="{{ $item['quantity'] }}"
                                    min="1" max="20"
                                    class="form-control form-control-sm text-center qty-input"
                                    data-id="{{ $id }}"
                                    data-price="{{ $item['price'] }}"
                                    style="width:60px">
                                <button type="button"
                                    class="btn btn-outline-secondary btn-sm qty-plus"
                                    data-id="{{ $id }}">+</button>
                                <button type="submit" class="btn btn-sm btn-outline-primary">
                                    Update
                                </button>
                            </form>
                        </div>

                        {{-- Item Total --}}
                        <div class="col-auto">
                            <strong class="text-danger fs-5" id="item-total-{{ $id }}">
                                Rs.{{ number_format($item['price'] * $item['quantity'], 2) }}
                            </strong>
                        </div>

                        {{-- Remove --}}
                        <div class="col-auto">
                            <form action="{{ route('cart.remove', $id) }}" method="POST">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>

                    </div>
                </div>
                @endforeach

                {{-- Clear Cart --}}
                <form action="{{ route('cart.clear') }}" method="POST">
                    @csrf @method('DELETE')
                    <button class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-times me-1"></i> Clear Cart
                    </button>
                </form>

                {{-- View Offers Link --}}
                <div class="mt-3">
                    <a href="{{ route('offers') }}" class="text-danger fw-semibold text-decoration-none">
                        🎉 View all offers & coupons →
                    </a>
                </div>
            </div>

            {{-- ======================== --}}
            {{--     ORDER SUMMARY        --}}
            {{-- ======================== --}}
            <div class="col-lg-4">
                <div class="card p-4 sticky-top" style="top:80px">
                    <h5 class="mb-3">Order Summary</h5>
                    <hr>

                    {{-- ---- COUPON APPLIED ---- --}}
                    @if(session('coupon'))
                        <div class="alert alert-success py-2 mb-3 d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-tag me-1"></i>
                                <strong>{{ session('coupon')['code'] }}</strong> applied!<br>
                                <small>
                                    You save <strong>Rs.{{ number_format(session('coupon')['discount'], 2) }}</strong>
                                    @if(session('coupon')['type'] === 'percentage')
                                        ({{ session('coupon')['value'] }}% off)
                                    @endif
                                </small>
                            </div>
                            <form action="{{ route('coupon.remove') }}" method="POST">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger rounded-pill">
                                    Remove
                                </button>
                            </form>
                        </div>

                    {{-- ---- COUPON FORM ---- --}}
                    @else
                        @if(session('coupon_error'))
                            <div class="alert alert-danger py-2 small mb-2">
                                <i class="fas fa-exclamation-circle me-1"></i>
                                {{ session('coupon_error') }}
                            </div>
                        @endif
                        @if(session('coupon_success'))
                            <div class="alert alert-success py-2 small mb-2">
                                <i class="fas fa-check-circle me-1"></i>
                                {{ session('coupon_success') }}
                            </div>
                        @endif

                        <form action="{{ route('coupon.apply') }}" method="POST" class="mb-3">
                            @csrf
                            <div class="input-group">
                                <input type="text"
                                    name="coupon_code"
                                    id="coupon_code_input"
                                    class="form-control"
                                    placeholder="Enter coupon code"
                                    style="border-radius:30px 0 0 30px;text-transform:uppercase"
                                    required>
                                <button class="btn btn-outline-danger"
                                    type="submit"
                                    style="border-radius:0 30px 30px 0">
                                    Apply
                                </button>
                            </div>
                        </form>

                        {{-- View Offers hint --}}
                        <div class="text-center mb-3">
                            <a href="{{ route('offers') }}" class="text-danger small text-decoration-none">
                                🎉 View available coupons
                            </a>
                        </div>
                    @endif

                    {{-- ---- PRICE BREAKDOWN ---- --}}
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Subtotal</span>
                        <strong id="summary-subtotal">
                            Rs.{{ number_format($total, 2) }}
                        </strong>
                    </div>

                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Delivery Fee</span>
                        <strong>Rs.50.00</strong>
                    </div>

                    {{-- Discount row — only when coupon applied --}}
                    @if(session('coupon'))
                        <div class="d-flex justify-content-between mb-2" style="color:#198754">
                            <span>
                                <i class="fas fa-tag me-1"></i>
                                Discount <small>({{ session('coupon')['code'] }})</small>
                            </span>
                            <strong id="summary-discount">
                                - Rs.{{ number_format($discount, 2) }}
                            </strong>
                        </div>
                    @endif

                    <hr>

                    {{-- Final Total --}}
                    <div class="d-flex justify-content-between mb-3 align-items-center">
                        <strong class="fs-5">Total</strong>
                        <strong class="text-danger fs-4" id="summary-total">
                            Rs.{{ number_format($finalTotal, 2) }}
                        </strong>
                    </div>

                    {{-- Savings badge --}}
                    @if(session('coupon'))
                        <div class="alert alert-success py-2 text-center small mb-3">
                            🎉 You are saving <strong>Rs.{{ number_format($discount, 2) }}</strong> on this order!
                        </div>
                    @endif

                    <a href="{{ route('orders.checkout') }}"
                        class="btn btn-primary w-100 rounded-pill py-2 fw-semibold">
                        Proceed to Checkout
                        <i class="fas fa-arrow-right ms-2"></i>
                    </a>
                </div>
            </div>

        </div>
    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    // Auto-fill coupon code if redirected from offers page
    var urlParams = new URLSearchParams(window.location.search);
    var couponParam = urlParams.get('coupon');
    if (couponParam) {
        var input = document.getElementById('coupon_code_input');
        if (input) input.value = couponParam.toUpperCase();
    }

    function recalculate() {
        var subtotal = 0;

        document.querySelectorAll('.qty-input').forEach(function (input) {
            var price = parseFloat(input.dataset.price);
            var qty   = parseInt(input.value) || 1;
            var id    = input.dataset.id;
            var total = price * qty;

            var el = document.getElementById('item-total-' + id);
            if (el) el.textContent = 'Rs.' + total.toFixed(2);

            subtotal += total;
        });

        var subtotalEl = document.getElementById('summary-subtotal');
        if (subtotalEl) subtotalEl.textContent = 'Rs.' + subtotal.toFixed(2);

        // ✅ Always defined at top of Blade
        var discount = parseFloat('{{ $discount }}') || 0;

        var totalEl = document.getElementById('summary-total');
        if (totalEl) totalEl.textContent = 'Rs.' + (subtotal + 50 - discount).toFixed(2);
    }

    document.querySelectorAll('.qty-plus').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var input = document.getElementById('qty-' + this.dataset.id);
            input.value = parseInt(input.value) + 1;
            recalculate();
        });
    });

    document.querySelectorAll('.qty-minus').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var input = document.getElementById('qty-' + this.dataset.id);
            if (parseInt(input.value) > 1) {
                input.value = parseInt(input.value) - 1;
                recalculate();
            }
        });
    });

    document.querySelectorAll('.qty-input').forEach(function (input) {
        input.addEventListener('input', recalculate);
        input.addEventListener('change', recalculate);
    });

});
</script>

@endsection