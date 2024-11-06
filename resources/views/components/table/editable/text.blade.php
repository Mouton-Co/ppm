@php
    $disabled = false;

    if (! auth()->user()->can_access) {
        $disabled = true;
    }
@endphp

<input
    class="cell-text tooltip-trigger {{ $disabled ? 'cursor-not-allowed' : 'cursor-text' }} h-full border-none bg-transparent px-0 text-sm hover:bg-sky-700 hover:text-white focus:outline-none focus:ring-0 {{ ! empty($widthFull) && $widthFull ? 'w-full' : '' }}"
    name="{{ $key }}"
    type="{{ $key == 'date_stamp' ? 'date' : 'text' }}"
    value="{{ $datum->$key }}"
    item-id="{{ $datum->id }}"
    model="{{ $model }}"
    @if (!empty($model::$structure[$key]['tooltip']) && $model::$structure[$key]['tooltip']) tooltip-id="{{ $datum->id . '-' . $key . '-tooltip' }}" @endif
    @if ($disabled) disabled @endif
>

@if (!empty($model::$structure[$key]['tooltip']) && $model::$structure[$key]['tooltip'] && !empty($datum->$key))
    <x-tooltip id="{{ $datum->id . '-' . $key . '-tooltip' }}">
        {!! $datum->$key !!}
    </x-tooltip>
@endif
