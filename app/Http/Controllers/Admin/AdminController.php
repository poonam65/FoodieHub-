<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\MenuItem;
use App\Models\Order;

class AdminController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'total_orders'     => Order::count(),
            'pending_orders'   => Order::where('status', 'pending')->count(),
            'total_revenue'    => Order::where('status', 'delivered')->sum('total'), // ✅ total
            'total_items'      => MenuItem::count(),
            'total_categories' => Category::count(),
        ];
        $recentOrders = Order::latest()->take(10)->get();
        return view('admin.dashboard', compact('stats', 'recentOrders'));
    }
}