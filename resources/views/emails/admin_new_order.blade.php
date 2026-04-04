<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; margin: 0; padding: 0; }
        .container { max-width: 600px; margin: 30px auto; background: #fff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.1); }
        .header { background: #1d1d1d; padding: 25px 30px; text-align: center; }
        .header h1 { color: #e63946; margin: 0; font-size: 24px; }
        .header p { color: #999; margin: 5px 0 0; font-size: 13px; }
        .alert-banner { background: #e63946; color: #fff; text-align: center; padding: 15px; font-size: 18px; font-weight: bold; }
        .body { padding: 30px; }
        .order-number { background: #fff8f0; border: 2px dashed #e63946; border-radius: 12px; padding: 15px; text-align: center; margin-bottom: 25px; }
        .order-number h2 { color: #e63946; margin: 0; font-size: 22px; }
        .order-number p { color: #999; margin: 5px 0 0; font-size: 12px; }
        .info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 25px; }
        .info-item { background: #f9f9f9; border-radius: 8px; padding: 12px; }
        .info-item label { font-size: 10px; color: #999; text-transform: uppercase; letter-spacing: 1px; display: block; margin-bottom: 4px; }
        .info-item p { margin: 0; font-size: 14px; color: #333; font-weight: bold; }
        .section-title { font-size: 13px; color: #e63946; text-transform: uppercase; letter-spacing: 1px; font-weight: bold; margin: 20px 0 10px; border-bottom: 1px solid #eee; padding-bottom: 8px; }
        table { width: 100%; border-collapse: collapse; }
        table th { background: #1d1d1d; color: #fff; padding: 10px 12px; text-align: left; font-size: 12px; }
        table td { padding: 10px 12px; border-bottom: 1px solid #f0f0f0; font-size: 13px; }
        .total-section { background: #fff8f0; border-radius: 8px; padding: 15px; margin-top: 15px; }
        .total-row { display: flex; justify-content: space-between; margin-bottom: 8px; font-size: 13px; }
        .total-row.final { font-size: 16px; font-weight: bold; color: #e63946; border-top: 2px solid #e63946; padding-top: 10px; margin-top: 10px; }
        .btn { display: block; width: fit-content; margin: 25px auto; background: #e63946; color: #fff; padding: 12px 35px; border-radius: 30px; text-decoration: none; font-weight: bold; font-size: 14px; }
        .footer { background: #1d1d1d; color: #666; text-align: center; padding: 20px; font-size: 12px; }
        .badge { display: inline-block; background: #fff3cd; color: #856404; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: bold; }
        .discount-row { color: #198754; }
    </style>
</head>
<body>
<div class="container">

    <!-- Header -->
    <div class="header">
        <h1>🍽️ FoodieHub Admin</h1>
        <p>Restaurant Management System</p>
    </div>

    <!-- Alert Banner -->
    <div class="alert-banner">
        🔔 New Order Received!
    </div>

    <!-- Body -->
    <div class="body">

        <!-- Order Number -->
        <div class="order-number">
            <h2>{{ $order->order_number }}</h2>
            <p>{{ $order->created_at->format('d M Y — h:i A') }}</p>
            <span class="badge">⏳ Pending</span>
        </div>

        <!-- Customer Info -->
        <div class="section-title">👤 Customer Information</div>
        <div class="info-grid">
            <div class="info-item">
                <label>Customer Name</label>
                <p>{{ $order->customer_name }}</p>
            </div>
            <div class="info-item">
                <label>Phone</label>
                <p>{{ $order->customer_phone }}</p>
            </div>
            <div class="info-item">
                <label>Email</label>
                <p>{{ $order->customer_email }}</p>
            </div>
            <div class="info-item">
                <label>Order Time</label>
                <p>{{ $order->created_at->format('h:i A') }}</p>
            </div>
        </div>

        <!-- Delivery Address -->
        <div class="section-title">📍 Delivery Address</div>
        <div class="info-item" style="margin-bottom:20px">
            <p style="font-weight:normal">{{ $order->delivery_address }}</p>
        </div>

        @if($order->notes)
        <div class="section-title">📝 Special Instructions</div>
        <div class="info-item" style="margin-bottom:20px">
            <p style="font-weight:normal;color:#e63946">{{ $order->notes }}</p>
        </div>
        @endif

        <!-- Order Items -->
        <div class="section-title">🛒 Order Items</div>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Item</th>
                    <th>Qty</th>
                    <th>Price</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td><strong>{{ $item->menuItem->name ?? 'Item' }}</strong></td>
                    <td>{{ $item->quantity }}</td>
                    <td>Rs.{{ number_format($item->price, 2) }}</td>
                    <td>Rs.{{ number_format($item->subtotal, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Total Section -->
        <div class="total-section">
            <div class="total-row">
                <span>Subtotal</span>
                <span>Rs.{{ number_format($order->subtotal, 2) }}</span>
            </div>
            <div class="total-row">
                <span>Delivery Fee</span>
                <span>Rs.{{ number_format($order->delivery_fee, 2) }}</span>
            </div>
            @if($order->discount > 0)
            <div class="total-row discount-row">
                <span>Discount ({{ $order->coupon_code }})</span>
                <span>- Rs.{{ number_format($order->discount, 2) }}</span>
            </div>
            @endif
            <div class="total-row final">
                <span>Total Amount</span>
                <span>Rs.{{ number_format($order->total, 2) }}</span>
            </div>
        </div>

        <!-- Action Button -->
        <a href="{{ url('/admin/orders') }}" class="btn">
            View Order in Admin Panel →
        </a>

        <p style="text-align:center;color:#999;font-size:12px">
            Jaldi action lo — customer wait kar raha hai! ⏰
        </p>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>FoodieHub Admin Notification System</p>
        <p>Ye email automatically generate hua hai</p>
    </div>
</div>
</body>
</html>