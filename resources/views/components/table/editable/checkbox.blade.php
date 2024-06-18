<input
    class="editable-cell-boolean"
    name="{{ $key }}"
    type="checkbox"
    item-id="{{ $datum->id }}"
    {{ $datum->$key ? 'checked' : '' }}
    {{ $datum->checkboxEnabled($key) ? '' : 'disabled' }}
>
