<input
    class="editable-cell-boolean"
    name="{{ $key }}"
    type="checkbox"
    item-id="{{ $datum->id }}"
    @if (! auth()->user()->can_access)
        disabled
    @endif
    {{ $datum->$key ? 'checked' : '' }}
    {{ $datum->checkboxEnabled($key) ? '' : 'disabled' }}
>
