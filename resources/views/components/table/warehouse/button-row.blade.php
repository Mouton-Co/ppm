@if (auth()->user()->role->hasPermission('update_warehouse'))
    <hr>
    <div class="my-2 flex flex-col gap-3 overflow-x-scroll">
        <form
            class="flex items-center gap-3"
            action="{{ route('parts.mark-as') }}"
            method="get"
        >
            @csrf

            <a href="{{ route('parts.unselect') }}" class="btn-sky text-nowrap max-w-fit">
                {{ __('Clear selected') }}
            </a>

            {{-- po number --}}
            <div class="flex items-center justify-start gap-2">
                <label
                    class="min-w-[95px] text-white"
                    for="po_number"
                >{{ __('PO Number') }}</label>
                <input
                    class="field-dark min-w-[300px]"
                    name="po_number"
                    type="text"
                    placeholder="PO number..."
                >
            </div>

            @php
                $options = App\Models\Part::$markedAs;
                array_unshift($options, '--Please Select--');
            @endphp

            <label
                class="min-w-fit text-white"
                for="mark_as"
            >{{ __('Mark as') }}</label>
            <select
                class="field-dark editable-cell-dropdown !w-[195px] cursor-pointer border-none bg-transparent focus:outline-none"
                name="mark_as"
            >
                @foreach ($options as $optionKey => $optionValue)
                    <option value="{{ $optionKey }}">
                        {{ $optionValue }}
                    </option>
                @endforeach
            </select>

            <button
                class="btn-sky text-nowrap min-w-fit max-w-fit"
                type="submit"
            >
                {{ __('Apply') }}
            </button>
        </form>
        {{-- form errors --}}
        @if ($errors->any())
            <div class="flex items-center gap-3">
                <x-icon.warning class="h-6 text-red-500" />
                <div class="text-red-500">
                    <ul class="list-inside list-disc">
                        @foreach ($errors->all() as $error)
                            <li class="text-sm">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif
    </div>
@endif
