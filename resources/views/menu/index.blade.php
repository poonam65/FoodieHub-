@extends('layouts.app')
@section('title', 'Our Menu')

@section('content')

<!-- Hero -->
<section style="background: linear-gradient(135deg, #e63946, #f4a261); padding: 60px 0;" class="text-white text-center">
    <div class="container">
        <h1 style="font-family:'Playfair Display',serif; font-size:2.5rem;">
            Explore Our Full Menu
        </h1>
        <p class="lead mt-2">All your favorites, just one tap away</p>
    </div>
</section>

<!-- Category Filter Bar -->
<section class="py-3 bg-white shadow-sm sticky-top" style="top:60px;z-index:99">
    <div class="container">
        <div class="d-flex gap-2 flex-wrap">  {{-- ✅ Backtick hataya --}}
            <a href="{{ route('menu.index') }}"
                class="btn btn-danger btn-sm rounded-pill">
                All Items
            </a>
            @foreach($categories as $cat)
                <a href="{{ route('menu.category', $cat->slug) }}"
                    class="btn btn-outline-secondary btn-sm rounded-pill">
                    {{ $cat->name }}
                    <span class="badge bg-danger ms-1">{{ $cat->menu_items_count }}</span>
                </a>
            @endforeach
        </div>
    </div>
</section>

<!-- All Menu Items -->
<section class="py-5">
    <div class="container">
        @forelse($categories as $cat)
            @if($cat->menuItems->count() > 0)
            <h4 class="mb-4 mt-3"
                style="font-family:'Playfair Display',serif;color:#e63946">
                {{ $cat->name }}
            </h4>
            <div class="row g-4 mb-5">
                @foreach($cat->menuItems as $item)
                <div class="col-md-3">
                    <div class="card h-100">
                        @if($item->image)
                            <img src="{{ asset('storage/'.$item->image) }}"
                                class="card-img-top"
                                style="height:200px;object-fit:cover;border-radius:16px 16px 0 0">
                        @else
                            <div style="height:200px;background:#ffd7d7;border-radius:16px 16px 0 0;display:flex;align-items:center;justify-content:center">
                                <i class="fas fa-hamburger fa-3x text-danger"></i>
                            </div>
                        @endif
                        <div class="card-body d-flex flex-column">
                            @if($item->is_featured)
                                <span class="badge bg-warning text-dark mb-2"
                                    style="width:fit-content">
                                    ⭐ Featured
                                </span>
                            @endif
                            <h6 class="card-title">{{ $item->name }}</h6>
                            <p class="card-text text-muted small flex-grow-1">
                                {{ Str::limit($item->description, 60) }}
                            </p>
                            @if(!$item->is_available)
                                <span class="badge bg-secondary mb-2"
                                    style="width:fit-content">
                                    Not Available
                                </span>
                            @endif
                            <div class="d-flex justify-content-between align-items-center mt-2">
                                <strong class="text-danger fs-5">
                                    Rs.{{ number_format($item->price, 2) }}
                                </strong>
                                @if($item->is_available)
                                    <form action="{{ route('cart.add', $item->id) }}"
                                        method="POST">
                                        @csrf
                                        <button class="btn btn-primary btn-sm rounded-pill px-3">
                                            <i class="fas fa-plus me-1"></i> Add
                                        </button>
                                    </form>
                                @else
                                    <button class="btn btn-secondary btn-sm rounded-pill px-3"
                                        disabled>
                                        Unavailable
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @endif
        @empty
            <div class="text-center py-5">
                <i class="fas fa-utensils fa-4x text-muted mb-3"></i>
                <h4 class="text-muted">Abhi koi item nahi hai</h4>
                <p class="text-muted">Admin se menu items add karwao!</p>
            </div>
        @endforelse
    </div>
</section>

@endsection