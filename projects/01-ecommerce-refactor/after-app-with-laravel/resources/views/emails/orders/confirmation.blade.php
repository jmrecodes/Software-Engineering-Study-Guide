<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Order Confirmation</title>
</head>
<body>
	<h1>Order Confirmation #{{ $order->id }}</h1>
	<p>Thanks for your order! We are preparing it for shipment.</p>
	<p><strong>Total:</strong> ${{ number_format($order->total, 2) }}</p>
	<p>We will notify you once your order is on the way.</p>
	<p>— {{ config('app.name') }}</p>
</body>
</html>
