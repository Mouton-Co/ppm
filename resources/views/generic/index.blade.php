@extends('layouts.dashboard')

@section('dashboard-content')
    {{-- heading --}}
    <div class="mb-2 flex items-center justify-between">
        <h2 class="text-lg text-white">{{ $heading ?? 'Items' }}</h2>
        <div class="flex cursor-pointer gap-2">
            <button
                class="h-7 rounded border border-gray-300 px-2 py-1 text-sm text-gray-300 shadow hover:border-sky-700 hover:text-sky-700"
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
            @if (in_array($key, array_keys($model::$structure)))
                @if ($model::$structure[$key]['type'] == 'text')
                    <x-filters.text-pill
                        value="{{ $value }}"
                        label="{{ $model::$structure[$key]['label'] }}"
                        key="{{ $key }}"
                    />
                @elseif ($model::$structure[$key]['type'] == 'dropdown')
                    <x-filters.dropdown-pill
                        label="{{ $model::$structure[$key]['label'] }}"
                        key="{{ $key }}"
                    >
                        @foreach ($model::$structure[$key]['filterable_options'] as $optionKey => $optionValue)
                            <option
                                value="{{ $optionKey }}"
                                @if ($optionKey == $value) selected @endif
                            >
                                {{ $optionValue }}
                            </option>
                        @endforeach
                    </x-filters.dropdown-pill>
                @elseif ($model::$structure[$key]['type'] == 'relationship')
                    <x-filters.dropdown-pill
                        label="{{ $model::$structure[$key]['label'] }}"
                        key="{{ $key }}"
                    >
                        @foreach ($model::$structure[$key]['relationship_model']::all() as $option)
                            <option
                                value="{{ $option->id }}"
                                @if ($option->id == $value) selected @endif
                            >
                                {{ $option->{$model::$structure[$key]['relationship_field']} }}
                            </option>
                        @endforeach
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
            class="absolute bottom-0 right-0 z-10 hidden w-full max-w-xs translate-y-full text-xs shadow-lg"
            id="filter-options"
            aria-hidden="true"
        >
            <div class="border-dark-field-border max-h-48 w-full overflow-hidden overflow-y-scroll rounded-md border">
                <div
                    class="border-dark-field-border flex h-8 w-full items-center border-b bg-gray-700 px-6 py-2 text-xs uppercase text-gray-400">
                    <span class="w-1/2">{{ __('Field') }}</span>
                    <span class="w-1/2">{{ __('Type') }}</span>
                </div>
                @foreach ($model::$structure as $key => $field)
                    @if ($field['filterable'])
                        @php
                            $hidden = request()->has($key) ? 'hidden' : '';
                        @endphp
                        <div
                            class="filter-option add-filter-pill border-dark-field-border {{ $hidden }} flex h-8 w-full cursor-pointer items-center border-b bg-gray-800 px-6 py-2 text-gray-300 hover:bg-gray-500"
                            model="{{ $model }}"
                            field="{{ $key }}"
                        >
                            <span class="w-1/2">{{ $field['label'] }}</span>
                            <span class="w-1/2">{{ $field['type'] }}</span>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    </form>

    {{-- table --}}
    <div class="relative overflow-x-auto rounded">
        <table class="border-dark-field-border w-full border text-left text-sm text-gray-400 rtl:text-right">
            {{-- headings --}}
            <thead class="bg-gray-700 text-xs uppercase text-gray-400">
                <tr>
                    @foreach (auth()->user()->table_configs['tables'][$table]['show'] as $key)
                        <th
                            class="px-6 py-2"
                            scope="col"
                        >
                            <div class="text-nowrap flex items-center">
                                {{ $model::$structure[$key]['label'] }}
                                @if ($field['sortable'])
                                    <form
                                        class="flex h-full w-full items-center justify-start"
                                        action="{{ route($indexRoute) }}"
                                        method="GET"
                                    >
                                        <input
                                            name="order_by"
                                            type="hidden"
                                            value="{{ $key }}"
                                        >
                                        <input
                                            name="order"
                                            type="hidden"
                                            value="{{ !empty(request()->query('order_by')) &&
                                            request()->query('order_by') == $key &&
                                            request()->query('order') == 'asc'
                                                ? 'desc'
                                                : 'asc' }}"
                                        >
                                        @foreach (request()->except(['order', 'order_by']) as $queryKey => $queryValue)
                                            <input
                                                name="{{ $queryKey }}"
                                                type="hidden"
                                                value="{{ $queryValue }}"
                                            />
                                        @endforeach
                                        <button type="submit">
                                            <x-icon.order-by
                                                class="ms-1.5 h-3 w-3 text-gray-300"
                                                :order="!empty(request()->query('order_by')) &&
                                                request()->query('order_by') == $key
                                                    ? request()->query('order')
                                                    : null"
                                            />
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </th>
                    @endforeach
                    <th
                        class="px-6 py-2"
                        scope="col"
                    >
                        {{-- column configurations --}}
                        <div class="relative flex items-center justify-end">
                            <x-icon.gear
                                class="h-3 w-3 cursor-pointer hover:text-sky-700"
                                id="column-config-toggle"
                            />
                            <div
                                class="border-dark-field-border absolute bottom-0 right-0 z-10 hidden w-64 translate-y-[107%] rounded border bg-gray-800 shadow-lg"
                                id="column-config"
                                aria-hidden="true"
                                table="{{ $table }}"
                            >
                                <x-filters.column-category>
                                    {{ __('Visible columns') }}
                                    <a
                                        class="absolute right-5"
                                        href="{{ route($indexRoute, request()->query()) }}"
                                    >
                                        <x-icon.refresh
                                            class="h-4 w-4 cursor-pointer text-sky-600 hover:text-sky-700"
                                            table="{{ $table }}"
                                            field="{{ $key }}"
                                        />
                                    </a>
                                </x-filters.column-category>
                                <div class="sortable-list flex w-full flex-col">
                                    @foreach (auth()->user()->table_configs['tables'][$table]['show'] as $key)
                                        <x-filters.column :key="$key">
                                            {{ $model::$structure[$key]['label'] }}
                                        </x-filters.column>
                                    @endforeach
                                    <x-filters.column-category
                                        key="hidden-columns"
                                        :orderable="true"
                                    >
                                        {{ __('Hidden columns') }}
                                    </x-filters.column-category>
                                    @foreach (auth()->user()->table_configs['tables'][$table]['hide'] as $key)
                                        <x-filters.column :key="$key">
                                            {{ $model::$structure[$key]['label'] }}
                                        </x-filters.column>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </th>
                </tr>
            </thead>
            {{-- rows --}}
            <tbody>
                @foreach ($data as $datum)
                    <tr class="border-b border-gray-700 bg-gray-800">
                        @foreach (auth()->user()->table_configs['tables'][$table]['show'] as $key)
                            <td class="text-nowrap px-6 py-2">
                                @if ($model::$structure[$key]['type'] == 'relationship')
                                    {{ $datum?->{$key}?->{$model::$structure[$key]['relationship_field']} ?? 'N/A' }}
                                @else
                                    {{ $datum?->{$key} ?? 'N/A' }}
                                @endif
                            </td>
                        @endforeach
                        <td class="flex items-center justify-end gap-3 px-6 py-2">
                            @if (!empty($model::$actions['edit']))
                                <a
                                    class="cursor-pointer text-sky-600 hover:text-sky-700"
                                    href="{{ route("$route.edit", $datum->id) }}"
                                >
                                    {{ __('Edit') }}
                                </a>
                            @endif
                            @if (!empty($model::$actions['delete']))
                                <span
                                    class="cursor-pointer text-red-500 hover:text-red-700"
                                    id="delete-button-{{ $datum->id }}"
                                >
                                    {{ __('Delete') }}
                                </span>
                                @include('components.delete-modal', [
                                    'model' => $datum,
                                    'route' => $route,
                                    'method' => 'DELETE',
                                    'message' => 'Are you sure you want to delete this item?',
                                ])
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- pagination --}}
    {{ $data->appends(request()->query())->links() }}

@endsection

{{-- used for column sorting --}}
@section('end-body-scripts')
    <script src="https://unpkg.com/sortablejs-make/Sortable.min.js"></script>
    <script src="https://code.jquery.com/jquery-2.2.4.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-sortablejs@latest/jquery-sortable.js"></script>
@endsection
