{{-- @extends('layouts.app')
@section('title', 'Home')

@section('content')

<!-- Hero Section -->
<section style="background: linear-gradient(135deg, #e63946, #f4a261); padding: 100px 0;" class="text-white text-center">
    <div class="container">
        <h1 style="font-family:'Playfair Display',serif; font-size:3.5rem;">
            Welcome to FoodieHub 🍽️
        </h1>
        <p class="lead mt-3 mb-4">
            Ghar baithe order karo — Fresh aur Tasty khana doorstep pe!
        </p>
        <a href="{{ route('menu.index') }}" class="btn btn-light btn-lg px-5 rounded-pill text-danger fw-semibold">
            Order Now
        </a>
    </div>
</section>

<!-- Categories Section -->
<section class="py-5">
    <div class="container">
        <h2 class="text-center mb-2" style="font-family:'Playfair Display',serif">
            Browse by Category
        </h2>
        <p class="text-center text-muted mb-4">Apni pasand ki category choose karo</p>
        <div class="row g-4 justify-content-center">
            @foreach($categories as $cat)
            <div class="col-6 col-md-2">
                <a href="{{ route('menu.category', $cat->slug) }}" class="text-decoration-none">
                    <div class="card text-center p-3 h-100">
                        @if($cat->image)
                            <img src="{{ asset('storage/'.$cat->image) }}"
                                class="rounded-circle mx-auto mb-2"
                                width="70" height="70"
                                style="object-fit:cover">
                        @else
                            <div class="rounded-circle mx-auto mb-2 bg-danger d-flex align-items-center justify-content-center"
                                style="width:70px;height:70px">
                                <i class="fas fa-utensils text-white"></i>
                            </div>
                        @endif
                        <h6 class="text-dark mb-0">{{ $cat->name }}</h6>
                        <small class="text-muted">{{ $cat->menu_items_count }} items</small>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Featured Items -->
@if($featured->count())
<section class="py-5 bg-white">
    <div class="container">
        <h2 class="text-center mb-2" style="font-family:'Playfair Display',serif">
            ⭐ Featured Items
        </h2>
        <p class="text-center text-muted mb-4">Hamare best selling items</p>
        <div class="row g-4">
            @foreach($featured as $item)
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
                        <span class="badge bg-warning text-dark mb-2" style="width:fit-content">
                            {{ $item->category->name }}
                        </span>
                        <h6 class="card-title">{{ $item->name }}</h6>
                        <p class="card-text text-muted small flex-grow-1">
                            {{ Str::limit($item->description, 60) }}
                        </p>
                        <div class="d-flex justify-content-between align-items-center mt-2">
                            <strong class="text-danger fs-5">
                                Rs.{{ number_format($item->price, 2) }}
                            </strong>
                            <form action="{{ route('cart.add', $item->id) }}" method="POST">
                                @csrf
                                <button class="btn btn-primary btn-sm rounded-pill px-3">
                                    <i class="fas fa-plus me-1"></i> Add
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <div class="text-center mt-4">
            <a href="{{ route('menu.index') }}" class="btn btn-outline-danger btn-lg rounded-pill px-5">
                View Full Menu →
            </a>
        </div>
    </div>
</section>
@endif

<!-- Why Choose Us -->
<section class="py-5">
    <div class="container">
        <h2 class="text-center mb-5" style="font-family:'Playfair Display',serif">
            Why Choose FoodieHub?
        </h2>
        <div class="row g-4 text-center">
            <div class="col-md-4">
                <div class="card p-4 h-100">
                    <i class="fas fa-truck fa-3x text-danger mb-3"></i>
                    <h5>Fast Delivery</h5>
                    <p class="text-muted">30 minute mein ghar tak delivery!</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card p-4 h-100">
                    <i class="fas fa-leaf fa-3x text-success mb-3"></i>
                    <h5>Fresh Ingredients</h5>
                    <p class="text-muted">Roz fresh ingredients se khana banate hain.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card p-4 h-100">
                    <i class="fas fa-star fa-3x text-warning mb-3"></i>
                    <h5>Best Quality</h5>
                    <p class="text-muted">Quality mein koi compromise nahi!</p>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection --}}