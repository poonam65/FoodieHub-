@extends('layouts.app')
@section('title', $category->name)

@section('content')

<!-- Hero -->
<section style="background: linear-gradient(135deg, #e63946, #f4a261); padding: 60px 0;" class="text-white text-center">
    <div class="container">
        <h1 style="font-family:'Playfair Display',serif; font-size:2.5rem;">
            {{ $category->name }}
        </h1>
        <p class="lead mt-2">Hamare best {{ $category->name }} items</p>
    </div>
</section>

<!-- Category Filter -->
<section class="py-3 bg-white shadow-sm sticky-top" style="top:60px;z-index:99">
    <div class="container">
        <div class="d-flex gap-2 flex-wrap">
            <a href="{{ route('menu.index') }}"
                class="btn btn-outline-danger btn-sm rounded-pill">
                All
            </a>
            @foreach($categories as $cat)
                <a href="{{ route('menu.category', $cat->slug) }}"
                    class="btn btn-sm rounded-pill {{ $cat->id == $category->id ? 'btn-danger' : 'btn-outline-secondary' }}">
                    {{ $cat->name }}
                </a>
            @endforeach
        </div>
    </div>
</section>

<!-- Items -->
<section class="py-5">
    <div class="container">
        @if($items->isEmpty())
            <div class="text-center py-5">
                <i class="fas fa-utensils fa-4x text-muted mb-3"></i>
                <h4 class="text-muted">Is category mein koi item nahi hai</h4>
                <a href="{{ route('menu.index') }}" class="btn btn-primary mt-3 rounded-pill px-5">
                    Back to Menu
                </a>
            </div>
        @else
            <div class="row g-4">
                @foreach($items as $item)
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

            <div class="mt-4">
                {{ $items->links() }}
            </div>
        @endif
    </div>
</section>

@endsection