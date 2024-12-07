<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ __('Document') }}</title>
</head>

<body>

    <p>{{ __('Good day,') }}</p>
    <p>
        {{ __("Kindly note that PO number {$order->po_number} is due today.") }}
    </p>

    <p>{{ __('Kindly select the appropriate option below:') }}</p>
    <ol>
        <li><a href="#">{{ __('The order is complete, please send a driver/courier from PPM to pick it up.') }}</a></li>
        <li><a href="#">{{ __('The order is complete, we will deliver to PPM.') }}</a></li>
        <li><a href="#">{{ __('The order is not yet complete.') }}</a></li>
    </ol>

    <table
        style="border-collapse: collapse; margin-top: 25px; font-size: 1rem; font-family: courier; min-width: 400px; box-shadow: 0 0 20px rgba(0, 0, 0, 0.15);">
        <thead>
            <tr style="background-color: #374151; color: #9ca3af; text-align: left;">
                <th style="padding: 2px 15px;">{{ __('Part Name') }}</th>
                <th style="padding: 2px 15px;">{{ __('Qty') }}</th>
                <th style="padding: 2px 15px;">{{ __('Material') }}</th>
                <th style="padding: 2px 15px;">{{ __('Material Thickness') }}</th>
                <th style="padding: 2px 15px;">{{ __('Stage') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($order->combined_parts as $name => $part)
            <tr style="border-bottom: 1px solid #dddddd; font-size: 0.9rem">
                <td style="padding: 2px 15px;">{{ $name }}</td>
                <td style="padding: 2px 15px;">{{ $part['quantity_ordered'] }}</td>
                <td style="padding: 2px 15px;">{{ $part['material'] }}</td>
                <td style="padding: 2px 15px;">{{ $part['material_thickness'] }}</td>
                <td style="padding: 2px 15px;">{{ $part['stage'] }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <p>{{ __('Kind regards,') }}</p>

    <footer style="color: #1f2937;">
        <p style="font-weight: 800; font-size: 1.1rem; padding-bottom: 0px; margin-bottom: 0px;">
            {{ __('PPM ERP System') }}
        </p>
        <span style="font-size: 0.8rem;">
            {{ __('Pro Project Machinery (Pty) Ltd.') }}
        </span>
    </footer>
</body>

</html>
