@extends('layouts.app')
@section('title', 'Search Results')

@section('content')

<!-- Search Hero -->
<section style="background: linear-gradient(135deg, #e63946, #f4a261); padding: 50px 0;" class="text-white text-center">
    <div class="container">
        <h2 style="font-family:'Playfair Display',serif" class="mb-4">
            Search Menu Items
        </h2>
        <!-- Search Form -->
        <form action="{{ route('menu.search') }}" method="GET">
            <div class="row justify-content-center">
                <div class="col-md-5">
                    <input type="text"
                        name="q"
                        value="{{ $query }}"
                        class="form-control form-control-lg rounded-pill px-4"
                        placeholder="Search pizza, burger, chai...">
                </div>
                <div class="col-md-3">
                    <select name="category" class="form-select form-select-lg rounded-pill px-4">
                        <option value="">All Categories</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}"
                                {{ $categoryId == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-dark btn-lg rounded-pill w-100">
                        <i class="fas fa-search me-2"></i>Search
                    </button>
                </div>
            </div>
        </form>
    </div>
</section>

<!-- Search Results -->
<section class="py-5">
    <div class="container">

        <!-- Result Count -->
        @if($query || $categoryId)
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="mb-0">
                    @if($query)
                        "<strong class="text-danger">{{ $query }}</strong>" ke results —
                    @endif
                    <span class="badge bg-danger">{{ $items->total() }} items found</span>
                </h5>
                <a href="{{ route('menu.search') }}" class="btn btn-outline-secondary btn-sm rounded-pill">
                    Clear Search
                </a>
            </div>
        @endif

        <!-- Results Grid -->
        @if($items->count() > 0)
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
                            <span class="badge bg-warning text-dark mb-2" style="width:fit-content">
                                {{ $item->category->name }}
                            </span>
                            <h6 class="card-title">
                                {{-- ✅ Search word highlight --}}
                                @if($query)
                                    {!! str_ireplace(
                                        $query,
                                        '<mark>'.$query.'</mark>',
                                        $item->name
                                    ) !!}
                                @else
                                    {{ $item->name }}
                                @endif
                            </h6>
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

            <!-- Pagination -->
            <div class="mt-4">
                {{ $items->appends(request()->query())->links() }}
            </div>

        @else
            <!-- No Results -->
            <div class="text-center py-5">
                <i class="fas fa-search fa-4x text-muted mb-3"></i>
                <h4 class="text-muted">
                    @if($query)
                        "{{ $query }}" ke liye koi result nahi mila!
                    @else
                        Kuch search karo!
                    @endif
                </h4>
                <p class="text-muted">Dusra keyword try karo</p>
                <a href="{{ route('menu.index') }}" class="btn btn-primary mt-3 rounded-pill px-5">
                    Full Menu Dekho
                </a>
            </div>
        @endif
    </div>
</section>

@endsection