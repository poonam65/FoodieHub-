<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; margin: 0; padding: 0; }
        .container { max-width: 600px; margin: 30px auto; background: #fff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.1); }
        .header { background: linear-gradient(135deg, #e63946, #f4a261); padding: 30px; text-align: center; color: #fff; }
        .header h1 { margin: 0; font-size: 28px; }
        .header p { margin: 5px 0 0; opacity: 0.9; }
        .body { padding: 30px; }
        .success-icon { text-align: center; margin: 20px 0; }
        .success-icon span { font-size: 60px; }
        h2 { color: #333; font-size: 22px; margin-bottom: 5px; }
        .order-box { background: #fff8f0; border: 1px solid #f4a261; border-radius: 12px; padding: 20px; margin: 20px 0; }
        .order-box h3 { color: #e63946; margin: 0 0 15px; font-size: 16px; }
        table { width: 100%; border-collapse: collapse; }
        table th { background: #e63946; color: #fff; padding: 10px; text-align: left; font-size: 13px; }
        table td { padding: 10px; border-bottom: 1px solid #eee; font-size: 13px; }
        table tr:last-child td { border: none; }
        .total-row td { font-weight: bold; color: #e63946; font-size: 15px; }
        .info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin: 20px 0; }
        .info-item { background: #f9f9f9; border-radius: 8px; padding: 12px; }
        .info-item label { font-size: 11px; color: #999; text-transform: uppercase; display: block; margin-bottom: 4px; }
        .info-item p { margin: 0; font-size: 14px; color: #333; font-weight: bold; }
        .status-badge { display: inline-block; background: #fff3cd; color: #856404; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: bold; }
        .btn { display: block; width: fit-content; margin: 20px auto; background: #e63946; color: #fff; padding: 12px 30px; border-radius: 30px; text-decoration: none; font-weight: bold; text-align: center; }
        .footer { background: #1d1d1d; color: #999; text-align: center; padding: 20px; font-size: 12px; }
        .footer a { color: #f4a261; text-decoration: none; }
        .divider { border: none; border-top: 1px solid #eee; margin: 20px 0; }
        .savings { background: #d1e7dd; color: #0f5132; border-radius: 8px; padding: 10px 15px; margin: 10px 0; text-align: center; font-size: 13px; }
    </style>
</head>
<body>
<div class="container">

    <!-- Header -->
    <div class="header">
        <h1>🍽️ FoodieHub</h1>
        <p>Delicious food delivered to your door</p>
    </div>

    <!-- Body -->
    <div class="body">
        <div class="success-icon"><span>✅</span></div>
        <h2 style="text-align:center">Order Confirmed!</h2>
        <p style="text-align:center;color:#666">
            Namaste <strong>{{ $order->customer_name }}</strong>! <br>
            Aapka order receive ho gaya hai. Hum jald hi prepare karenge!
        </p>

        <!-- Order Info -->
        <div class="info-grid">
            <div class="info-item">
                <label>Order Number</label>
                <p>{{ $order->order_number }}</p>
            </div>
            <div class="info-item">
                <label>Order Date</label>
                <p>{{ $order->created_at->format('d M Y, h:i A') }}</p>
            </div>
            <div class="info-item">
                <label>Status</label>
                <p><span class="status-badge">Pending</span></p>
            </div>
            <div class="info-item">
                <label>Payment</label>
                <p>Cash on Delivery</p>
            </div>
        </div>

        <!-- Delivery Address -->
        <div class="order-box">
            <h3>📍 Delivery Address</h3>
            <p style="margin:0;color:#555">{{ $order->delivery_address }}</p>
            <p style="margin:5px 0 0;color:#555">
                📞 {{ $order->customer_phone }}
            </p>
        </div>

        <!-- Order Items -->
        <div class="order-box">
            <h3>🛒 Order Items</h3>
            <table>
                <thead>
                    <tr>
                        <th>Item</th>
                        <th>Qty</th>
                        <th>Price</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $item)
                    <tr>
                        <td>{{ $item->menuItem->name ?? 'Item' }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>Rs.{{ number_format($item->price, 2) }}</td>
                        <td>Rs.{{ number_format($item->subtotal, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <hr class="divider">

            <!-- Totals -->
            <table>
                <tr>
                    <td style="color:#666">Subtotal</td>
                    <td style="text-align:right">Rs.{{ number_format($order->subtotal, 2) }}</td>
                </tr>
                <tr>
                    <td style="color:#666">Delivery Fee</td>
                    <td style="text-align:right">Rs.{{ number_format($order->delivery_fee, 2) }}</td>
                </tr>
                @if($order->discount > 0)
                <tr>
                    <td style="color:#198754">
                        Discount ({{ $order->coupon_code }})
                    </td>
                    <td style="text-align:right;color:#198754">
                        - Rs.{{ number_format($order->discount, 2) }}
                    </td>
                </tr>
                @endif
                <tr class="total-row">
                    <td>Total</td>
                    <td style="text-align:right">Rs.{{ number_format($order->total, 2) }}</td>
                </tr>
            </table>
        </div>

        @if($order->discount > 0)
        <div class="savings">
            🎉 Aapne Rs.{{ number_format($order->discount, 2) }} bachaye is order mein!
        </div>
        @endif

        @if($order->notes)
        <div class="order-box">
            <h3>📝 Special Instructions</h3>
            <p style="margin:0;color:#555">{{ $order->notes }}</p>
        </div>
        @endif

        <p style="color:#666;font-size:13px;text-align:center;margin-top:20px">
            Koi sawaal ho to hume contact karo:<br>
            📞 +91 98765 43210 |
            ✉️ hello@foodiehub.com
        </p>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>© {{ date('Y') }} FoodieHub. All rights reserved.</p>
        <p>
            <a href="#">Website</a> •
            <a href="#">Menu</a> •
            <a href="#">Contact</a>
        </p>
    </div>
</div>
</body>
</html>