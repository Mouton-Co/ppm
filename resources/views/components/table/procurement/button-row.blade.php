<hr>
<div class="my-2 flex gap-3 overflow-x-scroll">
    <form
        action="{{ route('parts.generate-po-numbers') }}"
        method="post"
    >
        @csrf
        <button
            class="btn-sky text-nowrap min-w-fit"
            type="submit"
        >
            {{ __('Generate PO') }}
        </button>
    </form>

    <form
        class="flex items-center gap-3"
        action="{{ route('parts.autofill-suppliers') }}"
        method="post"
    >
        @csrf
        <button
            class="btn-sky text-nowrap min-w-fit max-w-fit"
            type="submit"
        >
            {{ __('Autofill suppliers') }}
        </button>

        @php
            $options = App\Models\Supplier::all()->pluck('name', 'id')->toArray();
            array_unshift($options, '--Please Select--');
        @endphp

        <label
            class="min-w-fit text-white"
            for="lc_supplier"
        >{{ __("Lasercut Supplier") }}</label>
        <select
            class="field-dark editable-cell-dropdown !w-[195px] cursor-pointer border-none bg-transparent focus:outline-none"
            name="lc_supplier"
        >
            @foreach ($options as $optionKey => $optionValue)
                <option value="{{ $optionKey }}">
                    {{ $optionValue }}
                </option>
            @endforeach
        </select>
        <label
            class="min-w-fit text-white"
            for="part_supplier"
        >{{ __("Part Supplier") }}</label>
        <select
            class="field-dark editable-cell-dropdown !w-[195px] cursor-pointer border-none bg-transparent focus:outline-none"
            name="part_supplier"
        >
            @foreach ($options as $optionKey => $optionValue)
                <option value="{{ $optionKey }}">
                    {{ $optionValue }}
                </option>
            @endforeach
        </select>
    </form>
</div>
