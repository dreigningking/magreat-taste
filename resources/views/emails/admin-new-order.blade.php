<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Order Received - #{{ $order->id }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #007bff;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .order-info {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .order-details {
            margin-bottom: 20px;
        }
        .order-items {
            margin-bottom: 20px;
        }
        .item {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #eee;
        }
        .total {
            font-weight: bold;
            font-size: 18px;
            text-align: right;
            padding: 20px 0;
            border-top: 2px solid #007bff;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            color: #666;
            font-size: 14px;
        }
        .action-button {
            display: inline-block;
            background-color: #007bff;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>🆕 New Order Received!</h1>
        <p>Order #{{ $order->id }} has been placed and requires your attention.</p>
    </div>

    <div class="order-info">
        <h2>Order #{{ $order->id }}</h2>
        <p><strong>Order Date:</strong> {{ $order->created_at->format('F j, Y \a\t g:i A') }}</p>
        <p><strong>Status:</strong> <span style="color: #ffc107; font-weight: bold;">{{ ucfirst($order->status) }}</span></p>
        <p><strong>Delivery Type:</strong> {{ ucfirst($order->delivery_type) }}</p>
        @if($order->delivery_type === 'delivery')
            <p><strong>Delivery Address:</strong> {{ $order->delivery_address }}</p>
            <p><strong>Delivery Date:</strong> {{ \Carbon\Carbon::parse($order->delivery_date)->format('F j, Y') }}</p>
            <p><strong>Delivery Time:</strong> {{ \Carbon\Carbon::parse($order->delivery_time)->format('g:i A') }}</p>
        @endif
    </div>

    <div class="order-details">
        <h3>Customer Information</h3>
        <p><strong>Name:</strong> {{ $order->name }}</p>
        <p><strong>Email:</strong> {{ $order->email }}</p>
        <p><strong>Phone:</strong> {{ $order->phone }}</p>
    </div>

    <div class="order-items">
        <h3>Order Items ({{ $totalItems }} items)</h3>
        @foreach($orderItems as $item)
        <div class="item">
            <div>
                <strong>{{ $item->meal->name ?? $item->food->name ?? 'Unknown Item' }}</strong>
                @if($item->foodSize)
                    <br><small>Size: {{ $item->foodSize->name }}</small>
                @endif
                <br><small>Quantity: {{ $item->quantity }}</small>
            </div>
            <div>
                ₦{{ number_format($item->price, 2) }}
            </div>
        </div>
        @endforeach
    </div>

    <div class="total">
        <strong>Total Amount: ₦{{ number_format($order->total, 2) }}</strong>
    </div>

    <div style="text-align: center;">
        <a href="{{ url('/dashboard/orders/' . $order->id) }}" class="action-button">View Order Details</a>
    </div>

    <div class="footer">
        <p>Please process this order promptly to ensure customer satisfaction.</p>
        <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
    </div>
</body>
</html>
