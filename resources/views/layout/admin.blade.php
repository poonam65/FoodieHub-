<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin — @yield('title')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        body { background: #f4f6f9; }
        .sidebar { width: 260px; min-height: 100vh; background: #1d1d1d; position: fixed; top: 0; left: 0; padding-top: 20px; z-index: 100; }
        .sidebar .brand { color: #e63946; font-size: 1.5rem; padding: 0 20px 20px; border-bottom: 1px solid #333; display: block; }
        .sidebar .nav-link { color: #ccc; padding: 12px 20px; display: flex; align-items: center; gap: 10px; text-decoration: none; }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { background: #e63946; color: #fff; border-radius: 0 30px 30px 0; }
        .main-content { margin-left: 260px; padding: 30px; }
        .topbar { background: #fff; padding: 15px 20px; border-radius: 12px; margin-bottom: 24px; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 2px 10px rgba(0,0,0,0.06); }
        .card { border: none; box-shadow: 0 4px 20px rgba(0,0,0,0.08); border-radius: 16px; }
    </style>
</head>
<body>
<div class="sidebar">
    <span class="brand mb-3">FoodieHub Admin</span>
    <nav class="nav flex-column mt-2">
        <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
            <i class="fas fa-tachometer-alt"></i> Dashboard
        </a>
        <a class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}" href="{{ route('admin.categories.index') }}">
            <i class="fas fa-tags"></i> Categories
        </a>
        <a class="nav-link {{ request()->routeIs('admin.menu-items.*') ? 'active' : '' }}" href="{{ route('admin.menu-items.index') }}">
            <i class="fas fa-utensils"></i> Menu Items
        </a>
        <a class="nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}" href="{{ route('admin.orders.index') }}">
            <i class="fas fa-shopping-bag"></i> Orders
        </a>
        <hr style="border-color:#444;margin:20px">
        <a class="nav-link" href="{{ route('home') }}">
            <i class="fas fa-globe"></i> View Website
        </a>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="nav-link border-0 bg-transparent w-100 text-start" style="color:#e63946">
                <i class="fas fa-sign-out-alt"></i> Logout
            </button>
        </form>
    </nav>
</div>

<div class="main-content">
    <div class="topbar">
        <h6 class="mb-0 fw-semibold">@yield('title', 'Admin Panel')</h6>
        <span class="text-muted small">Welcome, {{ Auth::user()->name ?? 'Admin' }}</span>
    </div>
    @foreach(['success','error'] as $type)
        @if(session($type))
            <div class="alert alert-{{ $type === 'error' ? 'danger' : $type }} alert-dismissible fade show">
                {{ session($type) }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
    @endforeach
    @yield('content')
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>