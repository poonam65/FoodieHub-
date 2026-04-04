<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\MenuItem;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $period = $request->period ?? 'month';

        // ✅ Date range set karo
        switch ($period) {
            case 'today':
                $startDate = now()->startOfDay();
                $endDate   = now()->endOfDay();
                break;
            case 'week':
                $startDate = now()->startOfWeek();
                $endDate   = now()->endOfWeek();
                break;
            case 'month':
                $startDate = now()->startOfMonth();
                $endDate   = now()->endOfMonth();
                break;
            case 'year':
                $startDate = now()->startOfYear();
                $endDate   = now()->endOfYear();
                break;
            default:
                $startDate = now()->startOfMonth();
                $endDate   = now()->endOfMonth();
        }

        // ✅ Sales Overview
        $overview = [
            'total_orders'    => Order::whereBetween('created_at', [$startDate, $endDate])->count(),
            'total_revenue'   => Order::whereBetween('created_at', [$startDate, $endDate])
                                    ->where('status', '!=', 'cancelled')
                                    ->sum('total'),
            'delivered_orders'=> Order::whereBetween('created_at', [$startDate, $endDate])
                                    ->where('status', 'delivered')->count(),
            'cancelled_orders'=> Order::whereBetween('created_at', [$startDate, $endDate])
                                    ->where('status', 'cancelled')->count(),
            'pending_orders'  => Order::whereBetween('created_at', [$startDate, $endDate])
                                    ->where('status', 'pending')->count(),
            'avg_order_value' => Order::whereBetween('created_at', [$startDate, $endDate])
                                    ->where('status', '!=', 'cancelled')
                                    ->avg('total') ?? 0,
            'total_customers' => User::where('is_admin', false)->count(),
            'new_customers'   => User::where('is_admin', false)
                                    ->whereBetween('created_at', [$startDate, $endDate])->count(),
        ];

        // ✅ Best Selling Items
        $bestSellingItems = OrderItem::select(
                'menu_item_id',
                DB::raw('SUM(quantity) as total_quantity'),
                DB::raw('SUM(subtotal) as total_revenue'),
                DB::raw('COUNT(DISTINCT order_id) as order_count')
            )
            ->with('menuItem.category')
            ->whereHas('order', function($q) use ($startDate, $endDate) {
                $q->whereBetween('created_at', [$startDate, $endDate])
                  ->where('status', '!=', 'cancelled');
            })
            ->groupBy('menu_item_id')
            ->orderByDesc('total_quantity')
            ->take(10)
            ->get();

        // ✅ Category wise Sales
        $categorySales = Category::withCount(['menuItems as total_orders' => function($q) use ($startDate, $endDate) {
                $q->whereHas('orderItems.order', function($q) use ($startDate, $endDate) {
                    $q->whereBetween('created_at', [$startDate, $endDate])
                      ->where('status', '!=', 'cancelled');
                });
            }])
            ->get()
            ->map(function($cat) use ($startDate, $endDate) {
                $revenue = OrderItem::whereHas('menuItem', function($q) use ($cat) {
                        $q->where('category_id', $cat->id);
                    })
                    ->whereHas('order', function($q) use ($startDate, $endDate) {
                        $q->whereBetween('created_at', [$startDate, $endDate])
                          ->where('status', '!=', 'cancelled');
                    })
                    ->sum('subtotal');

                $cat->revenue = $revenue;
                return $cat;
            })
            ->sortByDesc('revenue');

        // ✅ Daily Sales — Last 30 days
        $dailySales = Order::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as orders'),
                DB::raw('SUM(total) as revenue')
            )
            ->where('created_at', '>=', now()->subDays(30))
            ->where('status', '!=', 'cancelled')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // ✅ Order Status Distribution
        $statusDistribution = Order::select('status', DB::raw('COUNT(*) as count'))
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('status')
            ->get();

        // ✅ Recent Top Customers
        $topCustomers = Order::select(
                'customer_name',
                'customer_email',
                DB::raw('COUNT(*) as total_orders'),
                DB::raw('SUM(total) as total_spent')
            )
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('status', '!=', 'cancelled')
            ->groupBy('customer_name', 'customer_email')
            ->orderByDesc('total_spent')
            ->take(5)
            ->get();

        return view('admin.reports.index', compact(
            'overview',
            'bestSellingItems',
            'categorySales',
            'dailySales',
            'statusDistribution',
            'topCustomers',
            'period'
        ));
    }
}