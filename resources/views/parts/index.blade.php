@extends('layouts.dashboard')

@section('dashboard-content')

    {{-- title and search --}}
    <div class="flex justify-between mb-3">
        <h2>{{ __('Parts') }}</h2>
        <form action="{{ route('parts.index') }}" method="GET" class="relative">
            <input type="text" name="search" placeholder="Search..." value="{{ request()->query('search') ?? '' }}"
                class="field-dark min-w-[300px]">
            <input type="hidden" name="order_by" value="{{ request()->query('order_by') ?? 'created_at' }}">
            <input type="hidden" name="order_direction" value="{{ request()->query('order_direction') ?? 'asc' }}">
            <input type="hidden" name="page" value="{{ request()->query('page') ?? 1 }}">
            <button type="submit" class="text-light-gray absolute right-[4px] top-[4px]">
                <x-icon.search />
            </button>
        </form>
    </div>

    {{-- button row --}}
    <div class="flex gap-3">
        <form action="{{ route('parts.generate-po-numbers') }}" method="post">
            @csrf
            <button type="submit" class="btn-sky">
                {{ __('Generate PO') }}
            </button>
        </form>
    </div>

    {{-- index table --}}
    <div class="field-card mt-4 overflow-auto no-scrollbar">
        <table class="table-dark no-scrollbar">
            <caption class="hidden">{{ __('Parts index table') }}</caption>
            <thead>
                <tr>
                    @foreach (config('models.parts.columns') as $key => $field)
                        <th>
                            <span class="flex justify-between">
                                <span class="flex items-center gap-2">
                                    {{ $field['name'] }}
                                    @if ($field['sortable'])
                                        <form action="{{ route('parts.index') }}" method="GET">
                                            <input type="hidden" name="search"
                                                value="{{ request()->query('search') ?? '' }}">
                                            <input type="hidden" name="order_by" value="{{ $key }}">
                                            <input type="hidden" name="page"
                                                value="{{ request()->query('page') ?? 1 }}">
                                            <input type="hidden" name="order_direction"
                                                value="{{ !empty(request()->query('order_by')) &&
                                                request()->query('order_by') == $key &&
                                                request()->query('order_direction') == 'asc'
                                                    ? 'desc'
                                                    : 'asc' }}">
                                            <button type="submit">
                                                <x-icon.up-arrow
                                                    class="cursor-pointer h-[10px]
                                                    {{ !empty(request()->query('order_by')) &&
                                                    request()->query('order_by') == $key &&
                                                    request()->query('order_direction') == 'asc'
                                                        ? 'rotate-180'
                                                        : '' }}" />
                                            </button>
                                        </form>
                                    @endif
                                </span>
                            </span>
                        </th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="hover-cell">
                @php
                    $cell = 0;
                @endphp
                @foreach ($parts as $part)
                    <tr>
                        @foreach (config('models.parts.columns') as $key => $field)
                            <td>
                                @php
                                    $editable = !empty($field['editable']) && $field['editable'];
                                    $type = !empty($field['type']) ? $field['type'] : 'text';
                                @endphp
                                <span id="{{ $part->id . '-' . $key }}" class="relative w-full h-full
                                {{ $editable && $type == 'text' ?
                                'cell-edit hover:bg-sky-700 cursor-text hover:shadow-inner' : '' }}
                                {{ $key == 'po_number' ? '!w-[140px]' : '' }}">
                                    @php
                                        $value = str_contains($key, '->')
                                            ? App\Http\Services\ModelService::nestedValue($part, $key)
                                            : $part->$key;
                                    @endphp

                                    @if ($editable)
                                        @switch($type)
                                            @case('text')
                                                <input type="{{ $key == 'date_stamp' ? 'date' : 'text' }}"
                                                    name="{{ $key }}" value="{{ $value }}"
                                                    class="w-auto h-full bg-transparent border-none
                                                    focus:ring-0 focus:outline-none editable-cell-text"
                                                    part-id="{{ $part->id }}">
                                                @break
                                            @case('select')
                                                @php

                                                    $options = $field['options']['model']::all()->pluck(
                                                        $field['options']['label'],
                                                        $field['options']['value']
                                                    )->toArray();

                                                    if (
                                                        !empty($field['options']['nullable']) &&
                                                        $field['options']['nullable']
                                                    ) {
                                                        array_unshift($options, '--Please Select--');
                                                    }
                                                @endphp
                                                <select name="{{ $key }}" class="field bg-transparent border-none
                                                !ring-0 !w-[195px] focus:ring-0 focus:outline-none cursor-pointer
                                                editable-cell-dropdown"
                                                part-id="{{ $part->id }}">
                                                    @foreach ($options as $optionKey => $optionValue)
                                                        <option value="{{ $optionKey }}"
                                                        @if ($optionKey === $part->supplier_id) selected @endif>
                                                            {{ $optionValue }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @break
                                            @case('boolean')
                                                <input type="checkbox" name="{{ $key }}"
                                                    class="editable-cell-boolean"
                                                    part-id="{{ $part->id }}" {{ $value ? 'checked' : '' }}
                                                    {{ $part->checkboxEnabled($key) ? '' : 'disabled' }}>
                                                @break
                                        @endswitch
                                    @else
                                        @if (!empty($field['format']))
                                            {{ $field['format'][$value] ?? '-' }}
                                        @else
                                            <div class="flex justify-start items-center gap-2">
                                                @if ($key == 'submission->submission_code')
                                                    <a href="{{ route(
                                                        'zip.download',
                                                        $part->submission->submission_code
                                                    ) }}" class="max-w-fit !p-0" download>
                                                        <x-icon.zip class="h-6 text-gray-300 hover:text-sky-700" />
                                                    </a>
                                                    <a href="{{ route('submissions.view', [
                                                        'id' => $part->submission->id,
                                                        'part' => $part->id
                                                    ]) }}" class="max-w-fit !p-0" target="_blank">
                                                        <x-icon.part class="h-5 text-gray-300 hover:text-sky-700" />
                                                    </a>
                                                @endif
                                                @if ($key == 'name' && !empty($part->pdf()))
                                                    <a href="{{ route('file.open', $part->pdf()->id) }}"
                                                    target="_blank" class="max-w-fit !p-0">
                                                        <x-icon.pdf class="h-6 text-gray-300 hover:text-sky-700" />
                                                    </a>
                                                @endif
                                                {{ $value ?? '-' }}
                                            </div>
                                        @endif
                                    @endif

                                </span>
                            </td>
                            @php
                                $cell++;
                            @endphp
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- pagination --}}
    {{ $parts->appends([
        'search' => request()->query('search') ?? '',
        'order_by' => request()->query('order_by') ?? 'name',
        'order_direction' => request()->query('order_direction') ?? 'asc',
    ])->links() }}
@endsection
