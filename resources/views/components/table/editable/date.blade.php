@php
    $disabled = false;

    if (! auth()->user()->can_access) {
        $disabled = true;
    }
@endphp

<input
    class="cell-date tooltip-trigger {{ $disabled ? 'cursor-not-allowed' : 'cursor-text' }} h-full border-none bg-transparent px-0 text-sm hover:bg-sky-700 hover:text-white focus:outline-none focus:ring-0 {{ ! empty($widthFull) && $widthFull ? 'w-full' : '' }}"
    name="{{ $key }}"
    type="date"
    value="{{ $datum->$key }}"
    item-id="{{ $datum->id }}"
    model="{{ $model }}"
    @if ($disabled) disabled @endif
>
