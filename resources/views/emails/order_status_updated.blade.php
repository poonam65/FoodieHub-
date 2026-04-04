<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; margin: 0; padding: 0; }
        .container { max-width: 600px; margin: 30px auto; background: #fff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.1); }
        .header { background: linear-gradient(135deg, #e63946, #f4a261); padding: 30px; text-align: center; color: #fff; }
        .header h1 { margin: 0; font-size: 28px; }
        .body { padding: 30px; text-align: center; }
        .status-section { margin: 30px 0; }
        .status-badge-large { display: inline-block; padding: 12px 30px; border-radius: 30px; font-size: 18px; font-weight: bold; margin: 15px 0; }
        .status-pending          { background: #fff3cd; color: #856404; }
        .status-confirmed        { background: #cff4fc; color: #055160; }
        .status-preparing        { background: #cfe2ff; color: #084298; }
        .status-out_for_delivery { background: #e2e3e5; color: #41464b; }
        .status-delivered        { background: #d1e7dd; color: #0f5132; }
        .status-cancelled        { background: #f8d7da; color: #842029; }
        .timeline { display: flex; justify-content: center; gap: 5px; margin: 20px 0; flex-wrap: wrap; }
        .timeline-step { text-align: center; padding: 8px 12px; border-radius: 20px; font-size: 11px; background: #f0f0f0; color: #999; }
        .timeline-step.active { background: #e63946; color: #fff; }
        .timeline-step.done { background: #d1e7dd; color: #0f5132; }
        .order-info { background: #f9f9f9; border-radius: 12px; padding: 20px; margin: 20px 0; text-align: left; }
        .btn { display: inline-block; background: #e63946; color: #fff; padding: 12px 30px; border-radius: 30px; text-decoration: none; font-weight: bold; margin-top: 20px; }
        .footer { background: #1d1d1d; color: #999; text-align: center; padding: 20px; font-size: 12px; }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <h1>🍽️ FoodieHub</h1>
        <p>Order Status Update</p>
    </div>

    <div class="body">
        <h2 style="color:#333">🔔 Order Status Updated!</h2>
        <p style="color:#666">
            Namaste <strong>{{ $order->customer_name }}</strong>!<br>
            Aapke order ka status update ho gaya hai.
        </p>

        <!-- Status Badge -->
        <div class="status-section">
            <p style="color:#999;font-size:13px">Current Status:</p>
            <span class="status-badge-large status-{{ $order->status }}">
                @switch($order->status)
                    @case('pending') ⏳ Pending @break
                    @case('confirmed') ✅ Confirmed @break
                    @case('preparing') 👨‍🍳 Preparing @break
                    @case('out_for_delivery') 🚀 Out for Delivery @break
                    @case('delivered') 🎉 Delivered @break
                    @case('cancelled') ❌ Cancelled @break
                @endswitch
            </span>
        </div>

        <!-- Timeline -->
        <div class="timeline">
            @php
                $steps = ['pending', 'confirmed', 'preparing', 'out_for_delivery', 'delivered'];
                $currentIndex = array_search($order->status, $steps);
            @endphp
            @foreach($steps as $index => $step)
                <div class="timeline-step
                    {{ $index < $currentIndex ? 'done' : '' }}
                    {{ $index == $currentIndex ? 'active' : '' }}">
                    {{ ucfirst(str_replace('_', ' ', $step)) }}
                </div>
            @endforeach
        </div>

        <!-- Order Info -->
        <div class="order-info">
            <p><strong>Order Number:</strong> {{ $order->order_number }}</p>
            <p><strong>Total:</strong> Rs.{{ number_format($order->total, 2) }}</p>
            <p><strong>Delivery Address:</strong> {{ $order->delivery_address }}</p>
        </div>

        @if($order->status === 'delivered')
            <p style="color:#198754;font-weight:bold;font-size:16px">
                🎉 Aapka khana deliver ho gaya! Enjoy karo!
            </p>
        @elseif($order->status === 'out_for_delivery')
            <p style="color:#e63946;font-weight:bold;font-size:16px">
                🚀 Aapka khana raaste mein hai! Thodi der mein pahunch jayega!
            </p>
        @elseif($order->status === 'preparing')
            <p style="color:#084298;font-weight:bold;font-size:16px">
                👨‍🍳 Aapka khana ban raha hai! Thoda wait karo!
            </p>
        @elseif($order->status === 'cancelled')
            <p style="color:#842029;font-weight:bold;font-size:16px">
                ❌ Aapka order cancel ho gaya. Kisi help ke liye contact karo.
            </p>
        @endif

        <p style="color:#666;font-size:13px;margin-top:20px">
            Koi sawaal ho:<br>
            📞 +91 98765 43210 |
            ✉️ hello@foodiehub.com
        </p>
    </div>

    <div class="footer">
        <p>© {{ date('Y') }} FoodieHub. All rights reserved.</p>
    </div>
</div>
</body>
</html>