@php
    switch ($direction ?? 'down') {
        case 'left' : $transform = 'transform="rotate(90)"' ; break;
        case 'right': $transform = 'transform="rotate(-90)"'; break;
        case 'up'   : $transform = 'transform="rotate(180)"'; break;
        default     : $transform = '';
    }
@endphp

<svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" {{ $attributes }} {!! $transform !!}>
    <g stroke-width="0"></g>
    <g stroke-linecap="round" stroke-linejoin="round"></g>
    <g>
        <path d="M7 10L12 15L17 10" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
        stroke-linejoin="round">
        </path>
    </g>
</svg>
