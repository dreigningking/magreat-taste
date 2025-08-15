<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Status Update - #{{ $order->id }}</title>
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
            background-color: #17a2b8;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .status-update {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: center;
        }
        .status-badge {
            display: inline-block;
            background-color: #28a745;
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: bold;
            font-size: 18px;
            margin: 10px 0;
        }
        .order-info {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
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
            border-top: 2px solid #17a2b8;
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
            background-color: #17a2b8;
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
        <h1>📋 Order Status Update</h1>
        <p>Your order status has been updated!</p>
    </div>

    <div class="status-update">
        <h2>Order #{{ $order->id }}</h2>
        @if($oldStatus)
            <p><strong>Previous Status:</strong> {{ ucfirst($oldStatus) }}</p>
        @endif
        <div class="status-badge">
            New Status: {{ ucfirst($newStatus) }}
        </div>
        <p>{{ $statusMessage }}</p>
    </div>

    <div class="order-info">
        <h3>Order Summary</h3>
        <p><strong>Order Date:</strong> {{ $order->created_at->format('F j, Y \a\t g:i A') }}</p>
        <p><strong>Delivery Type:</strong> {{ ucfirst($order->delivery_type) }}</p>
        @if($order->delivery_type === 'delivery')
            <p><strong>Delivery Address:</strong> {{ $order->delivery_address }}</p>
            <p><strong>Delivery Date:</strong> {{ \Carbon\Carbon::parse($order->delivery_date)->format('F j, Y') }}</p>
            <p><strong>Delivery Time:</strong> {{ \Carbon\Carbon::parse($order->delivery_time)->format('g:i A') }}</p>
        @endif
    </div>

    <div class="order-items">
        <h3>Order Items</h3>
        @foreach($order->orderItems as $item)
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
        <a href="{{ url('/orders/' . $order->id) }}" class="action-button">View Order Details</a>
    </div>

    <div class="footer">
        <p>If you have any questions about your order status, please contact us.</p>
        <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
    </div>
</body>
</html>
