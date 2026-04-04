<?php
namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Mail\OrderPlaced; 
use Illuminate\Support\Facades\Mail;  // ✅ Add
use App\Mail\NewOrderAlert;      // ✅ Admin email


use App\Models\MenuItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
public function checkout()
{
    $cart = session()->get('cart', []);
    if (empty($cart)) {
        return redirect()->route('cart.index')->with('error', 'Cart is empty!');
    }

    $subtotal = collect($cart)->sum(fn($item) => $item['price'] * $item['quantity']);
    $delivery = 50.00;

    // ✅ Discount calculate karo
    $discount = session('coupon') ? session('coupon')['discount'] : 0;
    $total    = $subtotal + $delivery - $discount;  // ✅ Discount minus karo

    return view('orders.checkout', compact('cart', 'subtotal', 'delivery', 'discount', 'total'));
}

public function placeOrder(Request $request)
{
    $request->validate([
        'customer_name'    => 'required|string|max:255',
        'customer_email'   => 'required|email',
        'customer_phone'   => 'required|string|max:20',
        'delivery_address' => 'required|string',
    ]);

    $cart = session()->get('cart', []);
    if (empty($cart)) {
        return redirect()->route('cart.index')->with('error', 'Cart is empty!');
    }

    $subtotal = collect($cart)->sum(fn($item) => $item['price'] * $item['quantity']);
    $delivery = 50.00;

    // ✅ Coupon discount
    $discount    = 0;
    $couponCode  = null;
    if (session('coupon')) {
        $discount   = session('coupon')['discount'];
        $couponCode = session('coupon')['code'];

        // Used count update karo
        $coupon = \App\Models\Coupon::where('code', $couponCode)->first();
        if ($coupon) {
            $coupon->increment('used_count');
        }
    }

    $total = $subtotal + $delivery - $discount;  // ✅ Discount minus karo

    $order = Order::create([
        'user_id'          => Auth::id(),
        'order_number'     => Order::generateOrderNumber(),
        'customer_name'    => $request->customer_name,
        'customer_email'   => $request->customer_email,
        'customer_phone'   => $request->customer_phone,
        'delivery_address' => $request->delivery_address,
        'subtotal'         => $subtotal,
        'delivery_fee'     => $delivery,
        'discount'         => $discount,     // ✅ Save discount
        'coupon_code'      => $couponCode,   // ✅ Save coupon code
        'total'            => $total,
        'notes'            => $request->notes,
    ]);

    foreach ($cart as $id => $item) {
        OrderItem::create([
            'order_id'     => $order->id,
            'menu_item_id' => $id,
            'quantity'     => $item['quantity'],
            'price'        => $item['price'],
            'subtotal'     => $item['price'] * $item['quantity'],
        ]);

        $order->load('items.menuItem');

        // ✅ Customer ko email
        try {
            Mail::to($order->customer_email)
                ->send(new OrderPlaced($order));
        } catch (\Exception $e) {
            // Continue even if email fails
        }

        // ✅ Admin ko email
        try {
            Mail::to(env('ADMIN_EMAIL'))
                ->send(new NewOrderAlert($order));
        } catch (\Exception $e) {
            // Continue even if email fails
        }

        session()->forget('coupon');
        session()->forget('cart');

        return redirect()->route('orders.success', $order->order_number);
    }
    

    session()->forget('coupon');
    session()->forget('cart');

    return redirect()->route('orders.success', $order->order_number);



    
}
    public function success($orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)
            ->with('items.menuItem')
            ->firstOrFail();
        return view('orders.success', compact('order'));
    }

    public function history()
    {
        $orders = Order::where('user_id', Auth::id())
            ->latest()
            ->paginate(10);
        return view('orders.history', compact('orders'));
    }

    public function show($orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)
            ->where('user_id', Auth::id())
            ->with('items.menuItem')
            ->firstOrFail();
        return view('orders.show', compact('order'));
    }

public function invoice($orderNumber)
{
    $order = Order::where('order_number', $orderNumber)
        ->with('items.menuItem')
        ->firstOrFail();
    return view('orders.invoice', compact('order'));
}




}