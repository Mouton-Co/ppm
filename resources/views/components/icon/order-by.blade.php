@php
    $path1 = 0.3;
    $path2 = 0.3;
    if (! empty($order)) {
        $path1 = $order == 'asc' ? 1 : 0.3;
        $path2 = $order == 'asc' ? 0.3 : 1;
    }
@endphp

<svg
    viewBox="0 0 24 24"
    {{ $attributes }}
>
    <path
        fill="currentColor"
        fill-opacity="{{ $path1 }}"
        d="M16.5 14.25h-9a.75.75 0 0 0-.53 1.28l4.5 4.5a.747.747 0 0 0 1.06 0l4.5-4.5a.75.75 0 0 0-.53-1.28Z"
    ></path>
    <path
        fill="currentColor"
        fill-opacity="{{ $path2 }}"
        d="M6.764 8.854a.75.75 0 0 0 .736.896h9a.75.75 0 0 0 .53-1.28l-4.5-4.5a.751.751 0 0 0-1.061 0l-4.5 4.5a.75.75 0 0 0-.205.384Z"
    ></path>
</svg>
