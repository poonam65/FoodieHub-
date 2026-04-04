<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FoodieHub — @yield('title', 'Order Food Online')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        :root { --primary: #e63946; }
        body { font-family: 'Poppins', sans-serif; background: #fff8f0; }
        .navbar-brand { font-family: 'Playfair Display', serif; color: var(--primary) !important; font-size: 1.8rem; }
        .btn-primary { background: var(--primary); border-color: var(--primary); }
        .btn-primary:hover { background: #c1121f; border-color: #c1121f; }
        .card { border: none; box-shadow: 0 4px 20px rgba(0,0,0,0.08); border-radius: 16px; transition: transform 0.2s; }
        .card:hover { transform: translateY(-4px); }
        .badge-cart { position: absolute; top: -6px; right: -8px; background: var(--primary); color: #fff; border-radius: 50%; width: 20px; height: 20px; font-size: 11px; display: flex; align-items: center; justify-content: center; }
        footer { background: #1d1d1d; color: #ccc; }
    </style>
    @stack('styles')
</head>
<body>

{{-- Navbar --}}
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
    <div class="container">
        <a class="navbar-brand" href="{{ route('home') }}">🍽️ FoodieHub</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('home') ? 'text-danger fw-semibold' : '' }}"
                        href="{{ route('home') }}">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('menu.*') ? 'text-danger fw-semibold' : '' }}"
                        href="{{ route('menu.index') }}">Menu</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('offers') ? 'text-danger fw-semibold' : '' }}"
                        href="{{ route('offers') }}">
                        🎉 Offers
                        <span class="badge bg-danger">New</span>
                    </a>
                </li>
            </ul>

            {{-- Search Bar --}}
            <form action="{{ route('menu.search') }}" method="GET" class="d-flex me-3">
                <div class="input-group">
                    <input type="text"
                        name="q"
                        class="form-control"
                        placeholder="Search food..."
                        value="{{ request('q') }}"
                        style="border-radius:30px 0 0 30px;border-right:none;min-width:180px">
                    <button class="btn btn-danger" type="submit"
                        style="border-radius:0 30px 30px 0">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </form>

            <div class="d-flex align-items-center gap-2">
                {{-- Cart --}}
                <a href="{{ route('cart.index') }}" class="btn btn-outline-danger position-relative">
                    <i class="fas fa-shopping-cart"></i>
                    @php $cartCount = collect(session('cart', []))->sum('quantity'); @endphp
                    @if($cartCount > 0)
                        <span class="badge-cart">{{ $cartCount }}</span>
                    @endif
                </a>

                {{-- User --}}
                @auth
                    <div class="dropdown">
                        <button class="btn btn-outline-secondary dropdown-toggle"
                            data-bs-toggle="dropdown">
                            {{ Auth::user()->name ?? 'User' }}
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a class="dropdown-item" href="{{ route('orders.history') }}">
                                    <i class="fas fa-shopping-bag me-2"></i>My Orders
                                </a>
                            </li>
                            @if(Auth::check() && Auth::user()->is_admin)
                                <li>
                                    <a class="dropdown-item text-danger"
                                        href="{{ route('admin.dashboard') }}">
                                        <i class="fas fa-tachometer-alt me-2"></i>Admin Panel
                                    </a>
                                </li>
                            @endif
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button class="dropdown-item text-danger" type="submit">
                                        <i class="fas fa-sign-out-alt me-2"></i>Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="btn btn-outline-secondary btn-sm">Login</a>
                    <a href="{{ route('register') }}" class="btn btn-primary btn-sm">Register</a>
                @endauth
            </div>
        </div>
    </div>
</nav>

{{-- Flash Messages --}}
<div class="container mt-3">
    @foreach(['success','error','warning','info'] as $type)
        @if(session($type))
            <div class="alert alert-{{ $type === 'error' ? 'danger' : $type }} alert-dismissible fade show">
                {{ session($type) }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
    @endforeach
</div>

{{-- Page Content --}}
@yield('content')

{{-- Footer --}}
<footer class="py-5 mt-5">
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <h5 class="text-white" style="font-family:'Playfair Display',serif">
                    🍽️ FoodieHub
                </h5>
                <p class="small">Delicious food delivered to your door.</p>
            </div>
            <div class="col-md-4">
                <h6 class="text-white">Quick Links</h6>
                <ul class="list-unstyled small">
                    <li>
                        <a href="{{ route('home') }}"
                            class="text-secondary text-decoration-none">Home</a>
                    </li>
                    <li>
                        <a href="{{ route('menu.index') }}"
                            class="text-secondary text-decoration-none">Menu</a>
                    </li>
                    <li>
                        <a href="{{ route('offers') }}"
                            class="text-secondary text-decoration-none">Offers</a>
                    </li>
                </ul>
            </div>
            <div class="col-md-4">
                <h6 class="text-white">Contact</h6>
                <p class="small">
                    <i class="fas fa-phone me-2"></i>+91 98765 43210
                </p>
                <p class="small">
                    <i class="fas fa-envelope me-2"></i>hello@foodiehub.com
                </p>
            </div>
        </div>
        <hr class="border-secondary">
        <p class="text-center small mb-0">
            © {{ date('Y') }} FoodieHub. All rights reserved.
        </p>
    </div>
</footer>

{{-- Bootstrap JS --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

{{-- ✅ Stack scripts — Reports chart ke liye zaroori --}}
@stack('scripts')

</body>
</html>