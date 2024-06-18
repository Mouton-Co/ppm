<input
    class="cell-text h-full max-w-[80px] border-none bg-transparent focus:outline-none focus:ring-0"
    name="{{ $key }}"
    type="number"
    value="{{ $datum->$key }}"
    item-id="{{ $datum->id }}"
    model="{{ $model }}"
    @if (!empty($structure[$key]['min']) || $structure[$key]['min'] == 0) min="{{ $structure[$key]['min'] }}" @endif
>
