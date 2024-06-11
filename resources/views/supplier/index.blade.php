@extends('layouts.dashboard')

@section('custom-scripts')
    <link href="https://code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css" rel="stylesheet" type="text/css" />
    <link href="{{ asset('css/treeSortable.css') }}" rel="stylesheet" />
@endsection

@section('dashboard-content')
    {{-- heading --}}
    <div class="mb-2 flex items-center justify-between">
        <h2 class="text-lg text-white">{{ __('Suppliers') }}</h2>
        <div class="cursor-pointer">
            <button
                id="filter"
                class="rounded border border-gray-300 px-2 py-1 text-sm text-gray-300 shadow hover:border-sky-700 hover:text-sky-700">
                {{ __('Filter') }}
            </button>
            <button
                class="rounded border border-sky-700 bg-sky-700 px-2 py-1 text-sm text-white shadow hover:border-sky-600 hover:bg-sky-600">
                {{ __('Create new') }}
            </button>
        </div>
    </div>

    {{-- filter --}}
    <form
        id="form"
        class="relative mb-2 flex min-h-[2.819rem] w-full flex-wrap items-center justify-start gap-2 rounded border border-gray-600 bg-transparent p-2 pr-20"
        action="{{ route('suppliers.index') }}"
    >

        {{-- query filter --}}
        <input class="h-full w-full border-0 bg-transparent p-0 text-sm text-white focus:ring-0" name="query" type="text"
            placeholder="Search..." value="{{ request()->query('query') ?? '' }}">

        {{-- hidden query params --}}
        @if (request()->query())
            @foreach (request()->query() as $key => $value)
                @if (in_array($key, ['page', 'order', 'order_by']))
                    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                @endif
            @endforeach
        @endif

        {{-- display query filters inside search bar --}}
        @foreach (request()->query() as $key => $value)
            @if (in_array($key, array_keys($model::$structure)))
                @if ($model::$structure[$key]['filterable_type'] == 'text')
                    <x-filters.text-pill
                        label="{{ $model::$structure[$key]['label'] }}"
                        key="{{ $key }}"
                        value="{{ $value }}"
                    />
                @elseif ($model::$structure[$key]['filterable_type'] == 'dropdown')
                    <x-filters.dropdown-pill
                        label="{{ $model::$structure[$key]['label'] }}"
                        key="{{ $key }}"
                    >
                        @foreach ($model::$structure[$key]['filterable_options'] as $optionKey => $optionValue)
                            <option value="{{ $optionKey }}" @if ($optionKey == $value) selected @endif>
                                {{ $optionValue }}
                            </option>
                        @endforeach
                    </x-filters.dropdown-pill>
                @endif
            @endif
        @endforeach

        {{-- filter options --}}
        <div
            id="filter-options-toggle"
            class="absolute right-2 flex cursor-pointer gap-1 text-sm text-sky-600 hover:text-sky-700"
        >
            <x-icon.filters class="h-5 w-5" />
            <span>{{ __('Filter') }}</span>
        </div>
        <div
            id="filter-options"
            class="absolute bottom-0 right-0 z-10 hidden w-full max-w-xs translate-y-full text-xs shadow-lg"
            aria-hidden="true"
        >
            <div class="border-dark-field-border max-h-48 w-full overflow-hidden overflow-y-scroll rounded-md border">
                <div
                    class="bg-gray-700 border-dark-field-border flex h-8 w-full items-center border-b px-6 py-2 text-gray-400">
                    <span class="w-1/2">{{ __('Field') }}</span>
                    <span class="w-1/2">{{ __('Type') }}</span>
                </div>
                @foreach ($model::$structure as $key => $field)
                    @if ($field['filterable'])
                        @php
                            $hidden = request()->has($key) ? 'hidden' : '';
                        @endphp
                        <div
                            class="filter-option flex h-8 w-full cursor-pointer items-center bg-gray-800 px-6 py-2 text-gray-300 hover:bg-gray-500 add-filter-pill border-b border-dark-field-border {{ $hidden }}"
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
                    @foreach ($model::$structure as $key => $field)
                        <th class="px-6 py-2" scope="col">
                            <div class="text-nowrap flex items-center">
                                {{ $field['label'] }}
                                @if ($field['sortable'])
                                    <form action="{{ route('suppliers.index') }}" method="GET">
                                        <input type="hidden" name="order_by" value="{{ $key }}">
                                        <input
                                            type="hidden"
                                            name="order"
                                            value="{{
                                                ! empty(request()->query('order_by')) &&
                                                request()->query('order_by') == $key &&
                                                request()->query('order') == 'asc'
                                                    ? 'desc'
                                                    : 'asc'
                                                }}"
                                        >
                                        @foreach (request()->except(['order', 'order_by']) as $queryKey => $queryValue)
                                            <input type="hidden" name="{{ $queryKey }}" value="{{ $queryValue }}" />
                                        @endforeach
                                        <button type="submit">
                                            <x-icon.order-by class="ms-1.5 h-3 w-3" />
                                        </button>
                                    </form>
                                @endif

                            </div>
                        </th>
                    @endforeach
                    <th class="px-6 py-2" scope="col">
                        <div class="flex items-center justify-end">
                            <x-icon.gear class="h-3 w-3 cursor-pointer hover:text-sky-700" />
                        </div>
                    </th>
                </tr>
            </thead>
            {{-- rows --}}
            <tbody>
                @foreach ($suppliers as $supplier)
                    <tr class="border-b border-gray-700 bg-gray-800">
                        @foreach ($model::$structure as $key => $field)
                            <td class="text-nowrap px-6 py-2">
                                {{ $supplier->{$key} ?? 'N/A' }}
                            </td>
                        @endforeach
                        <td class="flex items-center justify-end gap-3 px-6 py-2">
                            <a class="cursor-pointer text-sky-600 hover:text-sky-700"
                                href="{{ route('suppliers.edit', $supplier->id) }}">
                                {{ __('Edit') }}
                            </a>
                            <span class="cursor-pointer text-red-500 hover:text-red-700">
                                {{ __('Delete') }}
                            </span>
                            @include('components.delete-modal', [
                                'model' => $supplier,
                                'route' => 'suppliers',
                                'method' => 'DELETE',
                                'message' => 'Are you sure you want to delete this supplier?',
                            ])
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- pagination --}}
    {{ $suppliers->appends(request()->query())->links() }}



    <ul id="tree"></ul>

@endsection

@section('end-body-scripts')
    {{-- <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script> --}}
    <script src="{{ asset('js/treeSortable.js') }}"></script>
@endsection