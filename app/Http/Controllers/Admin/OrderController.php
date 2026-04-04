<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Mail\OrderStatusUpdated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{
    // ✅ All orders list
    public function index(Request $request)
    {
        $query = Order::with('items')->latest();

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $orders = $query->paginate(15);

        // Stats for top
        $stats = [
            'total'    => Order::count(),
            'pending'  => Order::where('status', 'pending')->count(),
            'delivered'=> Order::where('status', 'delivered')->count(),
            'cancelled'=> Order::where('status', 'cancelled')->count(),
        ];

        return view('admin.orders.index', compact('orders', 'stats'));
    }

    // ✅ Single order detail
    public function show(Order $order)
    {
        $order->load('items.menuItem');
        return view('admin.orders.show', compact('order'));
    }

    // ✅ Update order status
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,preparing,out_for_delivery,delivered,cancelled'
        ]);

        $order->status = $request->status;
        $order->save();

        // Email send karo
        try {
            Mail::to($order->customer_email)
                ->send(new OrderStatusUpdated($order->load('items.menuItem')));
        } catch (\Exception $e) {
            // Continue
        }

        return back()->with('success', 'Order status updated!');
    }

    // ✅ Invoice
    public function invoice(Order $order)
    {
        $order->load('items.menuItem');
        return view('orders.invoice', compact('order'));
    }
}