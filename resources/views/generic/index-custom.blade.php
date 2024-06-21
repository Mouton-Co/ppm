@extends('layouts.dashboard')

@section('dashboard-content')
    @php
        $structure = $structure ?? $model::$structure;
    @endphp

    {{-- heading --}}
    <div class="mb-2 flex items-center justify-between">
        <h2 class="text-lg text-white">{{ $heading ?? 'Items' }}</h2>
        <div class="flex cursor-pointer gap-2">
            <button
                class="h-7 rounded border border-sky-600 px-2 py-1 text-sm text-sky-600 shadow hover:border-sky-700 hover:bg-sky-700 hover:text-white"
                id="filter"
            >
                {{ __('Filter') }}
            </button>
            @if (!empty($model::$actions['create']))
                <a
                    class="block h-7 rounded border border-sky-700 bg-sky-700 px-2 py-1 text-sm text-white shadow hover:border-sky-600 hover:bg-sky-600"
                    href="{{ route("$route.create") }}"
                >
                    {{ $model::$actions['create'] }}
                </a>
            @endif
        </div>
    </div>

    {{-- filter --}}
    <form
        class="relative mb-2 flex min-h-[2.819rem] w-full flex-wrap items-center justify-start gap-2 rounded border border-gray-600 bg-transparent p-2 pr-20"
        id="form"
        action="{{ route($indexRoute) }}"
    >

        {{-- query filter --}}
        <input
            class="h-full w-full border-0 bg-transparent p-0 text-sm text-white focus:ring-0"
            name="query"
            type="text"
            value="{{ request()->query('query') ?? '' }}"
            placeholder="Search..."
        >

        {{-- hidden query params --}}
        @if (request()->query())
            @foreach (request()->query() as $key => $value)
                @if (in_array($key, ['page', 'order', 'order_by']))
                    <input
                        name="{{ $key }}"
                        type="hidden"
                        value="{{ $value }}"
                    >
                @endif
            @endforeach
        @endif

        {{-- display query filters inside search bar --}}
        @foreach (request()->query() as $key => $value)
            @if (in_array($key, array_keys($structure)))
                @if ($structure[$key]['type'] == 'text')
                    <x-filters.text-pill
                        value="{{ $value }}"
                        label="{{ $structure[$key]['label'] }}"
                        key="{{ $key }}"
                    />
                @elseif ($structure[$key]['type'] == 'dropdown')
                    @if (!empty($structure[$key]['relationship']))
                        @php
                            $relationshipModel = $structure[$key]['relationship_model'];
                            $relationshipField = explode('.', $structure[$key]['relationship'])[1];
                        @endphp
                        <x-filters.dropdown-pill
                            label="{{ $structure[$key]['label'] }}"
                            key="{{ $key }}"
                        >
                            @foreach ($relationshipModel::orderBy($relationshipField)->get() as $option)
                                <option
                                    value="{{ $option->$relationshipField }}"
                                    @if ($option->$relationshipField == $value) selected @endif
                                >
                                    {{ $option->$relationshipField }}
                                </option>
                            @endforeach
                        </x-filters.dropdown-pill>
                    @else
                        <x-filters.dropdown-pill
                            label="{{ $structure[$key]['label'] }}"
                            key="{{ $key }}"
                        >
                            @if ($structure[$key]['filterable_options'] == 'custom')
                                @php
                                    $customKey = \Str::camel("get_custom_{$key}_attribute");
                                    $options = $model::$customKey();
                                @endphp
                            @else
                                @php
                                    $options = $structure[$key]['filterable_options'];
                                @endphp
                            @endif
                            @foreach ($options as $optionKey => $optionValue)
                                <option
                                    value="{{ $optionKey }}"
                                    @if ($optionKey == $value) selected @endif
                                >
                                    {{ $optionValue }}
                                </option>
                            @endforeach
                        </x-filters.dropdown-pill>
                    @endif
                @elseif ($structure[$key]['type'] == 'boolean')
                    <x-filters.dropdown-pill
                        label="{{ $structure[$key]['label'] }}"
                        key="{{ $key }}"
                    >
                        <option
                            value="0"
                            @if (0 == $value) selected @endif
                        >
                            {{ __('No') }}
                        </option>
                        <option
                            value="1"
                            @if (1 == $value) selected @endif
                        >
                            {{ __('Yes') }}
                        </option>
                    </x-filters.dropdown-pill>
                @endif
            @endif
        @endforeach

        {{-- filter options --}}
        <div
            class="absolute right-2 flex cursor-pointer gap-1 text-sm text-sky-600 hover:text-sky-700"
            id="filter-options-toggle"
        >
            <x-icon.filters class="h-5 w-5" />
            <span>{{ __('Filter') }}</span>
        </div>
        <div
            class="absolute bottom-0 right-0 z-10 hidden w-full max-w-sm translate-y-full text-xs shadow-lg"
            id="filter-options"
            aria-hidden="true"
        >
            <div class="border-dark-field-border w-full overflow-hidden overflow-y-scroll rounded-md border">
                <div
                    class="border-dark-field-border flex h-8 w-full items-center border-b bg-gray-700 px-6 py-2 text-xs uppercase text-gray-400">
                    <span class="w-1/2">{{ __('Field') }}</span>
                    <span class="w-1/2">{{ __('Type') }}</span>
                </div>
                @foreach ($structure as $key => $field)
                    @if (!empty($field['filterable']) && $field['filterable'])
                        @php
                            $hidden = request()->has($key) ? 'hidden' : '';
                        @endphp
                        <div
                            class="filter-option add-filter-pill border-dark-field-border {{ $hidden }} flex h-8 w-full cursor-pointer items-center border-b bg-gray-800 px-6 py-2 text-gray-300 hover:bg-gray-500"
                            model="{{ $model }}"
                            field="{{ $key }}"
                            structure="{{ json_encode($structure) }}"
                        >
                            <span class="w-1/2">{{ $field['label'] }}</span>
                            <span class="w-1/2">{{ $field['type'] }}</span>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    </form>

    {{-- custom slot --}}
    @if (!empty($slot))
        @include($slot)
    @endif

    {{-- pagination --}}
    {{ $data->appends(request()->query())->links() }}

@endsection
