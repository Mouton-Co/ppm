<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >
    <meta
        http-equiv="X-UA-Compatible"
        content="ie=edge"
    >
    <title>{{ __('Document') }}</title>
</head>

<body>

    <p>{{ __('Good day,') }}</p>

    <p>
        {{ __("Kindly note that PO number {$order?->po_number} has been marked by supplier {$order?->supplier?->name} as not yet ready.") }}
    </p>

    <p>
        {{ __('Reason:') }}
    </p>
    <p style="margin-left: 1rem; font-style: italic;">
        {{ $reason ?? '' }}
    </p>

    <p>{{ __("The new due date has been set to {$order?->due_date} which is in {$order?->due_days} day(s).") }}</p>

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
