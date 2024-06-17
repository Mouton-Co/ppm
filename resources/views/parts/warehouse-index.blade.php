@extends('layouts.dashboard')

@section('dashboard-content')

    {{-- title and search --}}
    <div class="flex justify-between mb-3">
        <h2>{{ __('Warehouse') }}</h2>
    </div>

    {{-- button row --}}
    <hr>
    <div class="flex flex-col gap-3 my-2 overflow-x-scroll">
        <form action="{{ route('parts.mark-as') }}" method="post" class="flex gap-3 items-center">
            @csrf

            {{-- po number --}}
            <div class="flex items-center justify-start gap-2">
                <label for="po_number" class="min-w-[95px] text-white">{{ __('PO Number') }}</label>
                <input type="text" name="po_number" placeholder="PO number..." class="field-dark min-w-[300px]">
            </div>

            @php
            $options = App\Models\Part::$markedAs;
            array_unshift($options, '--Please Select--');
            @endphp

            <label for="mark_as" class="min-w-fit text-white">{{ __("Mark as") }}</label>
            <select name="mark_as" class="field-dark bg-transparent border-none
            !w-[195px] focus:outline-none cursor-pointer
            editable-cell-dropdown">
                @foreach ($options as $optionKey => $optionValue)
                    <option value="{{ $optionKey }}">
                        {{ $optionValue }}
                    </option>
                @endforeach
            </select>

            <button type="submit" class="btn-sky min-w-fit max-w-fit text-nowrap">
                {{ __('Apply') }}
            </button>
        </form>
        {{-- form errors --}}
        @if ($errors->any())
            <div class="flex items-center gap-3">
                <x-icon.warning class="h-6 text-red-500" />
                <div class="text-red-500">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li class="text-sm">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif
    </div>

    {{-- filters --}}
    <hr>
    <form action="{{ route('parts.warehouse.index') }}" method="get">

        <div class="grid grid-cols-2 gap-2 my-2 items-center justify-start
        smaller-than-1090:grid-cols-1">
        
            {{-- status --}}
            <div class="flex items-center justify-start gap-2 smaller-than-711:flex-col smaller-than-711:items-start">
                @php
                    $options = App\Models\Part::$statuses;
                    $options['-'] = 'All';
                    $options['qc_issue'] = 'QC Issue';
                    ksort($options);
                @endphp
                <label for="status" class="min-w-[95px] text-white">{{ __('Status') }}</label>
                <select name="status" class="field-dark bg-transparent border-none focus:outline-none cursor-pointer">
                    @foreach ($options as $optionKey => $optionValue)
                        <option value="{{ $optionKey }}"
                        @if (
                            !empty(request()->query('status')) &&
                            request()->query('status') == $optionKey
                        ) selected @endif>
                            {{ $optionValue }}
                        </option>
                    @endforeach
                </select>
            </div>
        
            {{-- supplier --}}
            <div class="flex items-center justify-start gap-2 smaller-than-711:flex-col smaller-than-711:items-start">
                @php
                    $options = App\Models\Supplier::all()->pluck(
                        'name',
                        'id'
                    )->toArray();
                    $options['-'] = 'All';
                    ksort($options);
                @endphp
                <label for="supplier_id" class="min-w-[95px] text-white">{{ __('Supplier') }}</label>
                <select name="supplier_id" class="field-dark bg-transparent border-none focus:outline-none
                cursor-pointer">
                    @foreach ($options as $optionKey => $optionValue)
                        <option value="{{ $optionKey }}"
                        @if (
                            !empty(request()->query('supplier_id')) &&
                            request()->query('supplier_id') == $optionKey
                        ) selected @endif>
                            {{ $optionValue }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- submission --}}
            <div class="flex items-center justify-start gap-2 smaller-than-711:flex-col smaller-than-711:items-start">
                <label for="submission" class="min-w-[95px] text-white">{{ __('Submission') }}</label>
                <input type="text" name="submission" placeholder="Submission..."
                value="{{ request()->query('submission') ?? '' }}" class="field-dark">
            </div>

            {{-- search --}}
            <div class="flex items-center justify-start gap-2 smaller-than-711:flex-col smaller-than-711:items-start">
                <label for="search" class="min-w-[95px] text-white">{{ __('Search') }}</label>
                <input type="text" name="search" placeholder="Search..." value="{{ request()->query('search') ?? '' }}"
                    class="field-dark">
            </div>
            
            {{-- buttons --}}
            <div class="smaller-than-1090:hidden"></div>
            <div class="flex items-center justify-end gap-3">
                <input type="hidden" name="page" value="{{ request()->query('page') ?? 1 }}">
                <button type="submit" class="btn-sky max-w-fit">
                    {{ __('Filter') }}
                </button>
                <a href="{{ route('parts.warehouse.index') }}"
                class="btn-sky-light max-w-fit">
                    {{ __('Clear Filters') }}
                </a>
            </div>
        </div>

    </form>
    <hr class="mb-3">

    {{-- index table --}}
    <div class="field-card mt-4 overflow-auto no-scrollbar">
        <table class="table-dark no-scrollbar">
            <caption class="hidden">{{ __('Parts index table') }}</caption>
            <thead>
                <tr>
                    @foreach (config('models.parts-warehouse.columns') as $key => $field)
                        <th>
                            <span class="flex justify-between">
                                <div class="flex items-center gap-2">
                                    <span class="text-nowrap">
                                        {{ $field['name'] }}
                                    </span>
                                    @if ($field['sortable'])
                                        <form action="{{ route('parts.warehouse.index') }}" method="GET">
                                            <input type="hidden" name="search"
                                                value="{{ request()->query('search') ?? '' }}">
                                            <input type="hidden" name="order_by" value="{{ $key }}">
                                            <input type="hidden" name="page"
                                                value="{{ request()->query('page') ?? 1 }}">
                                            <input type="hidden" name="order"
                                                value="{{ !empty(request()->query('order_by')) &&
                                                request()->query('order_by') == $key &&
                                                request()->query('order') == 'asc'
                                                    ? 'desc'
                                                    : 'asc' }}">
                                            <input type="hidden" name="status"
                                                value="{{ request()->query('status') ?? '-' }}">
                                            <input type="hidden" name="supplier_id"
                                                value="{{ request()->query('supplier_id') ?? '-' }}">
                                            <input type="hidden" name="submission"
                                                value="{{ request()->query('submission') ?? '-' }}">
                                            <button type="submit">
                                                <x-icon.up-arrow
                                                    class="cursor-pointer h-[10px]
                                                    {{ !empty(request()->query('order_by')) &&
                                                    request()->query('order_by') == $key &&
                                                    request()->query('order') == 'asc'
                                                        ? 'rotate-180'
                                                        : '' }}" />
                                            </button>
                                        </form>
                                    @endif
                                </div>
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
                        @foreach (config('models.parts-warehouse.columns') as $key => $field)
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
                                                    $options = [];
                                                    $options['-'] = '--Please select--';
                                                    if ($field['options'] == '\App\Models\Supplier') {
                                                        $models = $field['options']::all()->pluck(
                                                            'name',
                                                        )->toArray();
                                                        foreach ($models as $model) {
                                                            $options[$model] = $model;
                                                        }
                                                    } else {
                                                        foreach ($field['options'] as $option) {
                                                            $options[$option] = $option;
                                                        }
                                                    }
                                                    ksort($options);
                                                @endphp
                                                <select name="{{ $key }}" class="field bg-transparent border-none
                                                !ring-0 !w-[195px] focus:ring-0 focus:outline-none cursor-pointer
                                                editable-cell-dropdown"
                                                part-id="{{ $part->id }}">
                                                    @foreach ($options as $optionKey => $optionValue)
                                                        <option value="{{ $optionKey }}"
                                                        @if ($optionKey === $part->$key) selected @endif>
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
                                            @case('life-cycle')
                                                @foreach (config(
                                                    'models.parts-warehouse.columns.part_lifecycle.options'
                                                ) as $optionKey => $optionValue)
                                                    <div class="flex justify-between gap-3"
                                                    id="{{ $part->id . '-' . $optionKey }}">
                                                        <label>{{ $optionValue }}</label>
                                                        <input type="checkbox" name="{{ $optionKey }}"
                                                            class="editable-cell-boolean"
                                                            part-id="{{ $part->id }}"
                                                            value="{{ $optionKey }}"
                                                            {{ $part->$optionKey ? 'checked' : '' }}
                                                            {{ $part->checkboxEnabled($optionKey) ? '' : 'disabled' }}>
                                                    </div>
                                                @endforeach
                                                @break
                                            @case('integer')
                                                <input type="number" name="{{ $key }}" value="{{ $value }}"
                                                class="max-w-[80px] h-full bg-transparent border-none
                                                focus:ring-0 focus:outline-none editable-cell-text"
                                                @if (!empty($field['min']) || $field['min'] == 0)
                                                    min="{{ $field['min'] }}"
                                                @endif
                                                part-id="{{ $part->id }}">
                                                @break
                                        @endswitch
                                    @else
                                        @if (!empty($field['format']))
                                            @if ($key == 'status' && $part->qc_issue)
                                                {{ 'QC Issue' }}
                                            @else
                                                {{ $field['format'][$value] ?? '-' }}
                                            @endif
                                        @else
                                            <div class="flex justify-start items-center gap-2">
                                                @if ($key == 'submission->submission_code')
                                                    <a href="{{ route(
                                                        'zip.download',
                                                        $part->submission->submission_code
                                                    ) }}" class="max-w-fit !p-0" download>
                                                        <x-icon.zip class="h-6 text-gray-300 hover:text-sky-700" />
                                                    </a>
                                                    <a href="{{ route('submissions.show', [
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
                                                @if ($type == 'life-cycle-stamps')
                                                    <div>
                                                        @foreach (config(
                                                        'models.parts-warehouse.columns.part_lifecycle_stamps.options'
                                                        ) as $optionKey => $optionValue)
                                                        <div class="flex justify-evenly gap-3">
                                                            <span class="!p-0">{{ $optionValue }}</span>
                                                            <span class="!p-0">{{ __('@') }}</span>
                                                            <span class="!p-0" id="{{ $part->id . '-' . $optionKey }}">
                                                                {{ $part->$optionKey }}
                                                            </span>
                                                        </div>
                                                        @endforeach
                                                    </div>
                                                @else
                                                    {{ $value ?? '-' }}
                                                @endif
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
        'order' => request()->query('order') ?? 'asc',
        'status' => request()->query('status') ?? '-',
        'supplier_id' => request()->query('supplier_id') ?? '-',
        'submission' => request()->query('submission') ?? '',
    ])->links() }}
@endsection
