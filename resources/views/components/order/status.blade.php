@php
    if (empty($status)) {
        $type = 'processing';
    }

    switch ($status) {
        case 'processing':
            $text = 'Processing';
            $style = 'bg-blue-50 text-blue-700 ring-blue-600/20';
            break;
        case 'ordered':
            $text = 'Ordered';
            $style = 'bg-green-50 text-green-700 ring-green-600/20';
            break;
        default:
            $text = 'Processing';
            $style = 'bg-blue-50 text-blue-700 ring-blue-600/20';
            break;
    }
@endphp

<span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium ring-1 ring-inset {{ $style }}">
    {{ $text }}
</span>