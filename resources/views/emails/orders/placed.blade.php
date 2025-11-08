<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xác nhận Đơn hàng</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
        }

        .container {
            width: 90%;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            font-size: 24px;
            color: #4f46e5;
        }

        .order-details {
            margin-top: 20px;
        }

        .order-details table {
            width: 100%;
            border-collapse: collapse;
        }

        .order-details th,
        .order-details td {
            border: 1px solid #ddd;
            padding: 8px;
        }

        .order-details th {
            background-color: #f4f4f4;
            text-align: left;
        }

        .total {
            font-size: 1.2em;
            font-weight: bold;
            margin-top: 10px;
            text-align: right;
        }

        .footer {
            margin-top: 30px;
            font-size: 0.9em;
            color: #777;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1 class="header">Cảm ơn bạn đã đặt hàng!</h1>
        <p>Chào {{ $order->user->name }},</p>
        <p>Đơn hàng <strong>#{{ $order->id }}</strong> của bạn đã được tiếp nhận và đang chờ xử lý. Chúng tôi sẽ giao
            hàng (COD) đến địa chỉ:</p>
        <blockquote>
            <strong>{{ $order->shipping_address }}</strong><br>
            <strong>SĐT: {{ $order->phone_number }}</strong>
        </blockquote>

        <div class="order-details">
            <h2>Chi tiết đơn hàng (Đây là phần bạn yêu cầu)</h2>
            <table>
                <thead>
                    <tr>
                        <th>Sản phẩm</th>
                        <th>Số lượng</th>
                        <th>Đơn giá</th>
                        <th>Thành tiền</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($order->items as $item)
                        <tr>
                            <td>{{ $item->product_name }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>{{ number_format($item->price, 0, ',', '.') }} đ</td>
                            <td>{{ number_format($item->price * $item->quantity, 0, ',', '.') }} đ</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" class="total">Tổng cộng:</td>
                        <td class="total">{{ number_format($order->total_amount, 0, ',', '.') }} đ</td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <p class="footer">
            Cảm ơn bạn đã tin tưởng SHOESHOP.
        </p>
    </div>
</body>

</html>
