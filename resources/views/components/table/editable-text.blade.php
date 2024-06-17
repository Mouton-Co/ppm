<input
    class="cell-text tooltip-trigger h-full w-full border-none hover:text-white bg-transparent focus:outline-none focus:ring-0 hover:bg-sky-700"
    name="{{ $key }}"
    type="{{ $key == 'date_stamp' ? 'date' : 'text' }}"
    value="{{ $datum->$key }}"
    item-id="{{ $datum->id }}"
    @if (!empty($model::$structure[$key]['tooltip']) && $model::$structure[$key]['tooltip']) tooltip-id="{{ $datum->id . '-' . $key . '-tooltip' }}" @endif
>

@if (!empty($model::$structure[$key]['tooltip']) && $model::$structure[$key]['tooltip'] && !empty($datum->$key))
    <x-tooltip id="{{ $datum->id . '-' . $key . '-tooltip' }}">
        {!! $datum->$key !!}
    </x-tooltip>
@endif
