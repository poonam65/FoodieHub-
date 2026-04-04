@extends('layouts.app')
@section('title', 'Offers & Coupons')

@section('content')

{{-- Hero Banner --}}
<div style="background: linear-gradient(135deg, #e52d27, #f97316); padding: 50px 0; text-align:center; color:#fff; margin-bottom:40px">
    <h1 style="font-family:'Playfair Display',serif; font-size:2.5rem; font-weight:700; margin-bottom:8px">
        🎉 Offers & Coupons
    </h1>
    <p style="font-size:1.1rem; opacity:0.9">Copy a coupon code and save on your next order!</p>
</div>

<div class="container pb-5">

    {{-- Success / Error Flash --}}
    @if(session('coupon_success'))
        <div class="alert alert-success alert-dismissible fade show rounded-3 mb-4" role="alert">
            <i class="fas fa-check-circle me-2"></i> {{ session('coupon_success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('coupon_error'))
        <div class="alert alert-danger alert-dismissible fade show rounded-3 mb-4" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i> {{ session('coupon_error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($coupons->isEmpty())
        <div class="text-center py-5">
            <i class="fas fa-tag fa-4x text-muted mb-3"></i>
            <h4 class="text-muted">No active offers right now</h4>
            <p class="text-muted">Check back soon for exciting deals!</p>
            <a href="{{ route('menu.index') }}" class="btn btn-primary rounded-pill px-5 mt-2">
                Browse Menu
            </a>
        </div>
    @else
        <div class="row g-4">
            @foreach($coupons as $coupon)
            <div class="col-md-6 col-lg-4">
                <div class="coupon-card h-100" style="
                    border: 2px dashed #e52d27;
                    border-radius: 16px;
                    background: #fff;
                    overflow: hidden;
                    box-shadow: 0 4px 15px rgba(229,45,39,0.08);
                    position: relative;
                ">
                    {{-- Top Color Bar --}}
                    <div style="background: linear-gradient(135deg,#e52d27,#f97316); padding:16px 20px; position:relative">
                        {{-- Discount Badge --}}
                        <span style="
                            background:#fff;
                            color:#e52d27;
                            font-size:12px;
                            font-weight:700;
                            padding:4px 12px;
                            border-radius:20px;
                            display:inline-block;
                            margin-bottom:8px;
                            text-transform:uppercase;
                            letter-spacing:0.5px
                        ">
                            @if($coupon->type === 'percentage')
                                {{ number_format($coupon->value, 0) }}% OFF
                            @else
                                Rs.{{ number_format($coupon->value, 0) }} OFF
                            @endif
                        </span>

                        {{-- Ticket Icon --}}
                        <div style="position:absolute;top:12px;right:16px;font-size:28px;opacity:0.3">🎟️</div>

                        {{-- Coupon Code --}}
                        <h3 style="color:#fff; font-weight:800; font-size:1.6rem; letter-spacing:2px; margin:0">
                            {{ $coupon->code }}
                        </h3>
                    </div>

                    {{-- Coupon Details --}}
                    <div style="padding:16px 20px">
                        <ul style="list-style:none; padding:0; margin:0 0 16px; font-size:13px; color:#555">
                            @if($coupon->min_order_amount > 0)
                            <li class="mb-2">
                                <i class="fas fa-check-circle text-success me-2"></i>
                                Min order: <strong>Rs.{{ number_format($coupon->min_order_amount, 0) }}</strong>
                            </li>
                            @endif

                            @if($coupon->max_discount)
                            <li class="mb-2">
                                <i class="fas fa-check-circle text-success me-2"></i>
                                Max discount: <strong>Rs.{{ number_format($coupon->max_discount, 0) }}</strong>
                            </li>
                            @endif

                            @if($coupon->expires_at)
                            <li class="mb-2">
                                <i class="fas fa-clock text-warning me-2"></i>
                                Expires: <strong>{{ $coupon->expires_at->format('d M Y') }}</strong>
                            </li>
                            @endif

                            @if($coupon->usage_limit)
                            <li class="mb-2">
                                <i class="fas fa-users text-info me-2"></i>
                                {{ $coupon->usage_limit - $coupon->used_count }} uses left
                            </li>
                            @endif
                        </ul>

                        {{-- Divider with circles --}}
                        <div style="position:relative; border-top:2px dashed #f0f0f0; margin:0 -20px 16px; overflow:visible">
                            <div style="position:absolute;left:-12px;top:-10px;width:20px;height:20px;background:#f8f8f8;border-radius:50%;border:2px dashed #e52d27"></div>
                            <div style="position:absolute;right:-12px;top:-10px;width:20px;height:20px;background:#f8f8f8;border-radius:50%;border:2px dashed #e52d27"></div>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="d-flex gap-2">
                            {{-- Copy Code Button --}}
                            <button
                                class="btn btn-outline-danger flex-grow-1 rounded-pill fw-semibold copy-btn"
                                style="font-size:13px; letter-spacing:1px"
                                data-code="{{ $coupon->code }}"
                                onclick="copyCode('{{ $coupon->code }}', this)">
                                <i class="fas fa-copy me-1"></i> {{ $coupon->code }}
                            </button>

                            {{-- Apply in Cart Button --}}
                            <form action="{{ route('coupon.apply') }}" method="POST" style="flex-shrink:0">
                                @csrf
                                <input type="hidden" name="coupon_code" value="{{ $coupon->code }}">
                                <button type="submit"
                                    class="btn btn-danger rounded-pill fw-semibold"
                                    style="font-size:13px; white-space:nowrap">
                                    Apply in Cart →
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @endif
</div>

{{-- Copy Code Toast --}}
<div id="copy-toast" style="
    position:fixed;
    bottom:30px;
    left:50%;
    transform:translateX(-50%);
    background:#2d2d2d;
    color:#fff;
    padding:10px 24px;
    border-radius:30px;
    font-size:14px;
    font-weight:500;
    display:none;
    z-index:9999;
    box-shadow:0 4px 20px rgba(0,0,0,0.2)
">
    ✅ Coupon code copied!
</div>

<script>
function copyCode(code, btn) {
    navigator.clipboard.writeText(code).then(function() {
        // Show toast
        var toast = document.getElementById('copy-toast');
        toast.style.display = 'block';
        setTimeout(function() { toast.style.display = 'none'; }, 2000);

        // Change button text temporarily
        var original = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-check me-1"></i> Copied!';
        btn.classList.remove('btn-outline-danger');
        btn.classList.add('btn-success');
        setTimeout(function() {
            btn.innerHTML = original;
            btn.classList.remove('btn-success');
            btn.classList.add('btn-outline-danger');
        }, 2000);
    });
}
</script>

@endsection