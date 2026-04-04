<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin — @yield('title')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        body { background: #f4f6f9; margin: 0; }

        /* Sidebar */
        .sidebar {
            width: 260px;
            min-height: 100vh;
            background: #1d1d1d;
            position: fixed;
            top: 0; left: 0;
            padding-top: 0;
            z-index: 100;
            overflow-y: auto;
        }

        /* Brand */
        .sidebar .brand {
            color: #e63946;
            font-size: 1.4rem;
            font-weight: bold;
            padding: 20px 20px 15px;
            border-bottom: 1px solid #333;
            display: block;
        }

        /* Nav Links */
        .sidebar .nav-link {
            color: #ccc;
            padding: 11px 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
            font-size: 14px;
            transition: all 0.2s;
        }
        .sidebar .nav-link:hover {
            background: rgba(230,57,70,0.15);
            color: #fff;
            border-radius: 0 30px 30px 0;
        }
        .sidebar .nav-link.active {
            background: #e63946;
            color: #fff;
            border-radius: 0 30px 30px 0;
        }
        .sidebar .nav-link i {
            width: 18px;
            text-align: center;
        }

        /* Section Label */
        .sidebar .nav-section {
            color: #666;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding: 15px 20px 5px;
        }

        /* Divider */
        .sidebar hr {
            border-color: #333;
            margin: 10px 20px;
        }

        /* Main Content */
        .main-content {
            margin-left: 260px;
            padding: 25px 30px;
            min-height: 100vh;
        }

        /* Topbar */
        .topbar {
            background: #fff;
            padding: 14px 20px;
            border-radius: 12px;
            margin-bottom: 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.06);
        }

        /* Cards */
        .card {
            border: none;
            box-shadow: 0 2px 15px rgba(0,0,0,0.08);
            border-radius: 16px;
        }

        /* Logout button */
        .logout-btn {
            background: none;
            border: none;
            color: #e63946;
            padding: 11px 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 14px;
            width: 100%;
            text-align: left;
            cursor: pointer;
            transition: all 0.2s;
        }
        .logout-btn:hover {
            background: rgba(230,57,70,0.15);
            border-radius: 0 30px 30px 0;
        }
    </style>
</head>
<body>

{{-- ✅ Sidebar --}}
<div class="sidebar">

    {{-- Brand --}}
    <a href="{{ route('admin.dashboard') }}" class="brand">
        🍽️ FoodieHub
    </a>

    <nav class="nav flex-column mt-2">

        {{-- Main --}}
        <span class="nav-section">Main</span>

        <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
            href="{{ route('admin.dashboard') }}">
            <i class="fas fa-tachometer-alt"></i>
            Dashboard
        </a>

        {{-- Catalogue --}}
        <span class="nav-section">Catalogue</span>

        <a class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}"
            href="{{ route('admin.categories.index') }}">
            <i class="fas fa-tags"></i>
            Categories
        </a>

        <a class="nav-link {{ request()->routeIs('admin.menu-items.*') ? 'active' : '' }}"
            href="{{ route('admin.menu-items.index') }}">
            <i class="fas fa-utensils"></i>
            Menu Items
        </a>

        {{-- Sales --}}
        <span class="nav-section">Sales</span>

        <a class="nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}"
            href="{{ route('admin.orders.index') }}">
            <i class="fas fa-shopping-bag"></i>
            Orders
        </a>

        <a class="nav-link {{ request()->routeIs('admin.coupons.*') ? 'active' : '' }}"
            href="{{ route('admin.coupons.index') }}">
            <i class="fas fa-ticket-alt"></i>
            Coupons
        </a>

        {{-- Engagement --}}
        <span class="nav-section">Engagement</span>

        <a class="nav-link {{ request()->routeIs('admin.reviews.*') ? 'active' : '' }}"
            href="{{ route('admin.reviews.index') }}">
            <i class="fas fa-star"></i>
            Reviews
        </a>

        <a class="nav-link {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}"
            href="{{ route('admin.reports.index') }}">
            <i class="fas fa-chart-bar"></i>
            Reports
        </a>

        <hr>

        {{-- Website Link --}}
        <a class="nav-link" href="{{ route('home') }}" target="_blank">
            <i class="fas fa-globe"></i>
            View Website
        </a>

        {{-- Logout --}}
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="logout-btn">
                <i class="fas fa-sign-out-alt"></i>
                Logout
            </button>
        </form>

    </nav>
</div>

{{-- ✅ Main Content --}}
<div class="main-content">

    {{-- Topbar --}}
    <div class="topbar">
        <div>
            <h6 class="mb-0 fw-semibold">@yield('title', 'Admin Panel')</h6>
            <small class="text-muted">FoodieHub Admin Panel</small>
        </div>
        <div class="d-flex align-items-center gap-3">
            <a href="{{ route('home') }}" class="btn btn-outline-secondary btn-sm rounded-pill" target="_blank">
                <i class="fas fa-globe me-1"></i> View Site
            </a>
            <div>
                <i class="fas fa-user-circle text-danger me-1"></i>
                <span class="fw-semibold">{{ Auth::check() ? Auth::user()->name : 'Admin' }}</span>
            </div>
        </div>
    </div>

    {{-- Flash Messages --}}
    @foreach(['success','error','warning','info'] as $type)
        @if(session($type))
            <div class="alert alert-{{ $type === 'error' ? 'danger' : $type }} alert-dismissible fade show mb-4">
                <i class="fas fa-{{ $type === 'success' ? 'check-circle' : 'exclamation-circle' }} me-2"></i>
                {{ session($type) }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
    @endforeach

    {{-- Page Content --}}
    @yield('content')

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>