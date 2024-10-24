<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <p>PO {{ $order->po_number }} has been confirmed by the client on {{ date('Y-m-d H:i:s') }}.</p>

    <p><strong>{{ __("PPM ERP System") }}</strong></p>
    <pre>{{ __("Pro Project Machinery (Pty) Ltd.") }}</pre>
</body>
</html>
