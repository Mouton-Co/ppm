<div>
    @foreach (config('models.parts-warehouse.columns.part_lifecycle_stamps.options') as $optionKey => $optionValue)
        <div class="flex justify-start gap-3 text-left">
            <span class="!p-0">{{ $optionValue }}</span>
            <span class="!p-0">{{ __('@') }}</span>
            <span
                class="!p-0"
                id="{{ $datum->id . '-' . $optionKey }}"
            >
                {{ $datum->$optionKey }}
            </span>
        </div>
    @endforeach
</div>
