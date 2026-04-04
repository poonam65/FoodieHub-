@extends('layouts.app')
@section('title', $menuItem->name)

@section('content')
<div class="container py-5">
    <div class="row g-5 align-items-center">
        <div class="col-md-5">
            @if($menuItem->image)
                <img src="{{ asset('storage/'.$menuItem->image) }}"
                    class="img-fluid rounded-3 w-100 shadow"
                    style="height:380px;object-fit:cover">
            @else
                <div style="height:380px;background:#ffd7d7;border-radius:16px;display:flex;align-items:center;justify-content:center">
                    <i class="fas fa-hamburger fa-5x text-danger"></i>
                </div>
            @endif
        </div>
        <div class="col-md-7">
            <span class="badge bg-warning text-dark mb-3 px-3 py-2">
                {{ $menuItem->category->name }}
            </span>
            <h2 style="font-family:'Playfair Display',serif" class="mb-3">
                {{ $menuItem->name }}
            </h2>
            <p class="text-muted fs-5">{{ $menuItem->description }}</p>
            <h3 class="text-danger my-4 fw-bold">
                Rs.{{ number_format($menuItem->price, 2) }}
            </h3>

            @if($menuItem->is_available)
                <form action="{{ route('cart.add', $menuItem->id) }}" method="POST" class="d-inline">
                    @csrf
                    <button class="btn btn-primary btn-lg rounded-pill px-5 me-2">
                        <i class="fas fa-cart-plus me-2"></i> Add to Cart
                    </button>
                </form>
            @else
                <button class="btn btn-secondary btn-lg rounded-pill px-5 me-2" disabled>
                    Not Available
                </button>
            @endif

            <a href="{{ route('menu.category', $menuItem->category->slug) }}"
                class="btn btn-outline-secondary btn-lg rounded-pill px-4">
                <i class="fas fa-arrow-left me-2"></i> Back
            </a>
        </div>
    </div>

    {{-- Reviews Section --}}
<hr class="my-5">
<div class="row">
    <div class="col-lg-8">
        <h4 style="font-family:'Playfair Display',serif" class="mb-4">
            ⭐ Customer Reviews
            <small class="text-muted fs-6">
                ({{ $menuItem->total_reviews }} reviews)
            </small>
        </h4>

        {{-- Average Rating --}}
        @if($menuItem->total_reviews > 0)
        <div class="card p-4 mb-4 text-center" style="max-width:300px">
            <h1 class="text-warning mb-0" style="font-size:4rem">
                {{ number_format($menuItem->average_rating, 1) }}
            </h1>
            <div class="text-warning fs-4">
                @for($i = 1; $i <= 5; $i++)
                    @if($i <= round($menuItem->average_rating))
                        ★
                    @else
                        ☆
                    @endif
                @endfor
            </div>
            <small class="text-muted">
                {{ $menuItem->total_reviews }} reviews
            </small>
        </div>
        @endif

        {{-- Reviews List --}}
        @forelse($menuItem->reviews()->with('user')->latest()->take(10)->get() as $review)
        <div class="card p-4 mb-3">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <div class="d-flex align-items-center gap-2">
                    <div style="width:40px;height:40px;background:#e63946;border-radius:50%;display:flex;align-items:center;justify-content:center;color:#fff;font-weight:bold">
                        {{ strtoupper(substr($review->user->name, 0, 1)) }}
                    </div>
                    <div>
                        <strong>{{ $review->user->name }}</strong>
                        <br>
                        <small class="text-muted">
                            {{ $review->created_at->diffForHumans() }}
                        </small>
                    </div>
                </div>
                <div class="text-warning fs-5">
                    @for($i = 1; $i <= 5; $i++)
                        {{ $i <= $review->rating ? '★' : '☆' }}
                    @endfor
                </div>
            </div>
            @if($review->comment)
                <p class="mb-0 text-muted">{{ $review->comment }}</p>
            @endif
        </div>
        @empty
        <div class="text-center py-4 text-muted">
            <i class="fas fa-star fa-3x mb-3 opacity-25"></i>
            <p>Abhi koi review nahi hai — pehle review do!</p>
        </div>
        @endforelse
    </div>
</div>

    <!-- Related Items -->
    @if($related->count())
    <hr class="my-5">
    <h4 style="font-family:'Playfair Display',serif" class="mb-4">
        Related Items
    </h4>
    <div class="row g-4">
        @foreach($related as $item)
        <div class="col-md-3">
            <div class="card h-100">
                @if($item->image)
                    <img src="{{ asset('storage/'.$item->image) }}"
                        class="card-img-top"
                        style="height:180px;object-fit:cover;border-radius:16px 16px 0 0">
                @else
                    <div style="height:180px;background:#ffd7d7;border-radius:16px 16px 0 0;display:flex;align-items:center;justify-content:center">
                        <i class="fas fa-hamburger fa-2x text-danger"></i>
                    </div>
                @endif
                <div class="card-body">
                    <h6>{{ $item->name }}</h6>
                    <div class="d-flex justify-content-between align-items-center">
                        <strong class="text-danger">
                            Rs.{{ number_format($item->price, 2) }}
                        </strong>
                        <form action="{{ route('cart.add', $item->id) }}" method="POST">
                            @csrf
                            <button class="btn btn-primary btn-sm rounded-pill px-3">
                                <i class="fas fa-plus"></i>
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
@endsection
