<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <p>Good day,</p>
    <p>
        Just a reminder that PO {{ $order->po_number }} is due in 5 days.
    </p>

    <table style='border-collapse: collapse; width: 99.9915%;' border='1'>
        <thead>
            <tr>
                <th>Part Name</th>
                <th>Qty</th>
                <th>Material</th>
                <th>Material Thickness</th>
                <th>Stage</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($order->combined_parts as $name => $part)
                <tr>
                    <td>{{ $name }}</td>
                    <td>{{ $part['quantity_ordered'] }}</td>
                    <td>{{ $part['material'] }}</td>
                    <td>{{ $part['material_thickness'] }}</td>
                    <td>{{ $part['stage'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <p>For any queries, please email jone@proproject.co.za</p>
    <p>Kind regards,</p>

    <p><strong>{{ __("PPM ERP System") }}</strong></p>
    <pre>{{ __("Pro Project Machinery (Pty) Ltd.") }}</pre>
</body>
</html>
