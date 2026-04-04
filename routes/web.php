<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\MenuItemController;
use App\Http\Controllers\Admin\CouponController as AdminCouponController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\ReviewController as AdminReviewController;
use App\Http\Controllers\Admin\ReportController;  // ✅ Add kiya

// -------------------------------------------------------
// Public Routes
// -------------------------------------------------------
Route::get('/', [MenuController::class, 'index'])->name('home');
Route::get('/menu', [MenuController::class, 'menu'])->name('menu.index');
Route::get('/menu/category/{category:slug}', [MenuController::class, 'category'])->name('menu.category');
Route::get('/menu/{menuItem:slug}', [MenuController::class, 'show'])->name('menu.show');
Route::get('/search', [MenuController::class, 'search'])->name('menu.search');

// -------------------------------------------------------
// Offers Page
// -------------------------------------------------------
Route::get('/offers', function () {
    $coupons = \App\Models\Coupon::where('is_active', true)
        ->where(function ($q) {
            $q->whereNull('expires_at')
              ->orWhere('expires_at', '>=', now());
        })
        ->where(function ($q) {
            $q->whereNull('usage_limit')
              ->orWhereRaw('used_count < usage_limit');
        })
        ->orderBy('created_at', 'desc')
        ->get();
    return view('offers', compact('coupons'));
})->name('offers');

// -------------------------------------------------------
// Cart Routes
// -------------------------------------------------------
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add/{id}', [CartController::class, 'add'])->name('cart.add');
Route::patch('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
Route::delete('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');

// -------------------------------------------------------
// Coupon Routes
// -------------------------------------------------------
Route::post('/coupon/apply', [CouponController::class, 'apply'])->name('coupon.apply');
Route::delete('/coupon/remove', [CouponController::class, 'remove'])->name('coupon.remove');

// -------------------------------------------------------
// Order Routes
// -------------------------------------------------------
Route::get('/checkout', [OrderController::class, 'checkout'])->name('orders.checkout');
Route::post('/checkout', [OrderController::class, 'placeOrder'])->name('orders.place');
Route::get('/order/success/{orderNumber}', [OrderController::class, 'success'])->name('orders.success');

// -------------------------------------------------------
// Dashboard Redirect
// -------------------------------------------------------
Route::get('/dashboard', function () {
    if (Auth::check() && Auth::user()->is_admin) {
        return redirect('/admin/dashboard');
    }
    return redirect('/');
})->middleware(['auth'])->name('dashboard');

// -------------------------------------------------------
// Auth Required — User Routes  ✅ Sirf ek group
// -------------------------------------------------------
Route::middleware('auth')->group(function () {
    // Orders
    Route::get('/my-orders', [OrderController::class, 'history'])->name('orders.history');
    Route::get('/my-orders/{orderNumber}', [OrderController::class, 'show'])->name('orders.show');

    // Reviews
    Route::get('/review/{orderNumber}', [ReviewController::class, 'create'])->name('reviews.create');
    Route::post('/review', [ReviewController::class, 'store'])->name('reviews.store');
    Route::delete('/review/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');
});

// -------------------------------------------------------
// Admin Routes  ✅ Sirf ek group — sab routes yahan
// -------------------------------------------------------
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // Categories
    Route::resource('categories', CategoryController::class);

    // Menu Items
    Route::resource('menu-items', MenuItemController::class);

    // Coupons
    Route::resource('coupons', AdminCouponController::class);

    // Orders
    // Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');

    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::patch('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');
    Route::get('/orders/{order}/invoice', [OrderController::class, 'invoice'])->name('orders.invoice');

    // Reviews
    Route::get('/reviews', [AdminReviewController::class, 'index'])->name('reviews.index');
    Route::patch('/reviews/{review}/toggle', [AdminReviewController::class, 'toggleApproval'])->name('reviews.toggle');
    Route::delete('/reviews/{review}', [AdminReviewController::class, 'destroy'])->name('reviews.destroy');

    // Reports  ✅ Yahan add kiya
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
});

require __DIR__ . '/auth.php';