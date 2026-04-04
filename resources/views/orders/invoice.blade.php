<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice — {{ $order->order_number }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; font-size: 14px; color: #333; }
        .invoice-box { max-width: 800px; margin: 30px auto; padding: 30px; border: 1px solid #eee; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; border-bottom: 3px solid #e63946; padding-bottom: 20px; }
        .logo { font-size: 28px; font-weight: bold; color: #e63946; }
        .invoice-title { text-align: right; }
        .invoice-title h2 { font-size: 24px; color: #e63946; }
        .invoice-title p { color: #666; font-size: 13px; }
        .info-section { display: flex; justify-content: space-between; margin-bottom: 30px; }
        .info-box { width: 48%; }
        .info-box h6 { color: #e63946; font-size: 13px; text-transform: uppercase; margin-bottom: 8px; border-bottom: 1px solid #eee; padding-bottom: 5px; }
        .info-box p { margin-bottom: 4px; font-size: 13px; color: #555; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        table thead tr { background: #e63946; color: white; }
        table thead th { padding: 10px 12px; text-align: left; font-size: 13px; }
        table tbody tr { border-bottom: 1px solid #eee; }
        table tbody tr:nth-child(even) { background: #f9f9f9; }
        table tbody td { padding: 10px 12px; font-size: 13px; }
        .totals { float: right; width: 280px; }
        .totals table { border: none; }
        .totals table td { padding: 6px 12px; border: none; }
        .totals table tr:last-child { background: #e63946; color: white; font-weight: bold; font-size: 15px; }
        .totals table tr:last-child td { padding: 10px 12px; }
        .status-badge { display: inline-block; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: bold; }
        .status-pending { background: #fff3cd; color: #856404; }
        .status-confirmed { background: #cff4fc; color: #055160; }
        .status-preparing { background: #cfe2ff; color: #084298; }
        .status-delivered { background: #d1e7dd; color: #0a3622; }
        .status-cancelled { background: #f8d7da; color: #842029; }
        .footer { margin-top: 40px; text-align: center; color: #999; font-size: 12px; border-top: 1px solid #eee; padding-top: 20px; }
        .print-btn { text-align: center; margin: 20px 0; }
        .print-btn button { background: #e63946; color: white; border: none; padding: 10px 30px; border-radius: 30px; font-size: 15px; cursor: pointer; margin: 0 5px; }
        .print-btn a { background: #6c757d; color: white; border: none; padding: 10px 30px; border-radius: 30px; font-size: 15px; text-decoration: none; margin: 0 5px; display: inline-block; }
        @media print {
            .print-btn { display: none; }
            .invoice-box { box-shadow: none; border: none; }
        }
    </style>
</head>
<body>

<!-- Print / Back Buttons -->
<div class="print-btn">
    <a href="{{ url()->previous() }}">← Back</a>
    <button onclick="window.print()">🖨️ Print Invoice</button>
</div>

<div class="invoice-box">

    <!-- Header -->
    <div class="header">
        <div class="logo">🍽️ FoodieHub</div>
        <div class="invoice-title">
            <h2>INVOICE</h2>
            <p>Order # {{ $order->order_number }}</p>
            <p>Date: {{ $order->created_at->format('d M Y, h:i A') }}</p>
            <p>
                Status:
                <span class="status-badge status-{{ $order->status }}">
                    {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                </span>
            </p>
        </div>
    </div>

    <!-- Info Section -->
    <div class="info-section">
        <div class="info-box">
            <h6>From</h6>
            <p><strong>FoodieHub Restaurant</strong></p>
            <p>123, Food Street, City</p>
            <p>Phone: +91 98765 43210</p>
            <p>Email: hello@foodiehub.com</p>
        </div>
        <div class="info-box">
            <h6>Bill To</h6>
            <p><strong>{{ $order->customer_name }}</strong></p>
            <p>{{ $order->customer_email }}</p>
            <p>{{ $order->customer_phone }}</p>
            <p>{{ $order->delivery_address }}</p>
        </div>
    </div>

    <!-- Items Table -->
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Item Name</th>
                <th>Price</th>
                <th>Qty</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $item->menuItem->name ?? 'Item Deleted' }}</td>
                <td>Rs.{{ number_format($item->price, 2) }}</td>
                <td>{{ $item->quantity }}</td>
                <td>Rs.{{ number_format($item->subtotal, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Totals -->
    <div class="totals">
        <table>
            <tr>
                <td>Subtotal</td>
                <td>Rs.{{ number_format($order->subtotal, 2) }}</td>
            </tr>
            <tr>
                <td>Delivery Fee</td>
                <td>Rs.{{ number_format($order->delivery_fee, 2) }}</td>
            </tr>
            @if($order->notes)
            <tr>
                <td colspan="2" style="color:#666;font-size:12px">
                    Note: {{ $order->notes }}
                </td>
            </tr>
            @endif
            <tr>
                <td>TOTAL</td>
                <td>Rs.{{ number_format($order->total, 2) }}</td>
            </tr>
        </table>
    </div>

    <div style="clear:both"></div>

    <!-- Footer -->
    <div class="footer">
        <p>Thank you for ordering from FoodieHub! 🍽️</p>
        <p>Visit us again — we’re always happy to welcome you!</p>
    </div>

</div>

</body>
</html>