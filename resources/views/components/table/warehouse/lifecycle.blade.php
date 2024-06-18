@foreach (config('models.parts-warehouse.columns.part_lifecycle.options') as $optionKey => $optionValue)
    <div
        class="flex justify-between gap-3 max-w-xs"
        id="{{ $datum->id . '-' . $optionKey }}"
    >
        <label for="">{{ $optionValue }}</label>
        <input
            class="editable-cell-boolean"
            name="{{ $optionKey }}"
            type="checkbox"
            value="{{ $optionKey }}"
            item-id="{{ $datum->id }}"
            {{ $datum->$optionKey ? 'checked' : '' }}
            {{ $datum->checkboxEnabled($optionKey) ? '' : 'disabled' }}
        >
    </div>
@endforeach
