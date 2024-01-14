@extends('layouts.dashboard')

@section('dashboard-content')
    {{-- title --}}
    <div class="flex justify-between items-center mb-2">
        <h2 class="text-left">{{ __('Submissions') }}</h2>
        <a href="{{ route('new.submission') }}" class="btn btn-sky max-w-fit">{{ __('Add submission') }}</a>
    </div>

    {{-- filters --}}
    <hr>
    <form action="{{ route('submissions.index') }}" method="get">

        <div class="grid grid-cols-3 gap-2 my-2 items-center justify-start
        smaller-than-1457:grid-cols-2 smaller-than-711:!grid-cols-1">
        
            {{-- submission type --}}
            <div class="flex items-center justify-start gap-2 smaller-than-711:flex-col smaller-than-711:items-start">
                @php
                    $options = config('dropdowns.submission_types');
                    $options['-'] = 'All';
                    ksort($options);
                @endphp
                <label for="submission_type" class="smaller-than-1457:min-w-[125px] text-white text-nowrap">
                    {{ __('Submission type') }}
                </label>
                <select name="submission_type" class="field bg-transparent border-none
                !ring-0 focus:ring-0 focus:outline-none cursor-pointer">
                    @foreach ($options as $optionKey => $optionValue)
                        <option value="{{ $optionKey }}"
                        @if (
                            !empty(request()->query('submission_type')) &&
                            request()->query('submission_type') == $optionKey
                        ) selected @endif>
                            {{ $optionValue }}
                        </option>
                    @endforeach
                </select>
            </div>
        
            {{-- unit number --}}
            <div class="flex items-center justify-start gap-2 smaller-than-711:flex-col smaller-than-711:items-start">
                @php
                    $options = config('dropdowns.unit_numbers');
                    $options['-'] = 'All';
                    ksort($options);
                @endphp
                <label for="current_unit_number" class="min-w-fit smaller-than-1457:min-w-[125px] text-white
                text-nowrap">
                    {{ __('Unit number') }}
                </label>
                <select name="current_unit_number" class="field bg-transparent border-none
                !ring-0 focus:ring-0 focus:outline-none cursor-pointer">
                    @foreach ($options as $optionKey => $optionValue)
                        <option value="{{ $optionKey }}"
                        @if (
                            !empty(request()->query('current_unit_number')) &&
                            request()->query('current_unit_number') == $optionKey
                        ) selected @endif>
                            {{
                                $optionValue == 'All' ?
                                $optionValue :
                                $optionKey . ' - ' . $optionValue
                            }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- search --}}
            <div class="flex items-center justify-start gap-2 smaller-than-711:flex-col smaller-than-711:items-start">
                <label for="search" class="min-w-fit smaller-than-1457:min-w-[125px] text-white text-nowrap">
                    {{ __('Search') }}
                </label>
                <input type="text" name="search" placeholder="Search..." value="{{ request()->query('search') ?? '' }}"
                    class="field-dark">
            </div>
            
            {{-- buttons --}}
            <div class="smaller-than-1457:hidden"></div><div class="smaller-than-1457:hidden"></div>
            <div class="flex items-center justify-end gap-3">
                <input type="hidden" name="page" value="{{ request()->query('page') ?? 1 }}">
                <button type="submit" class="btn-sky max-w-fit">
                    {{ __('Filter') }}
                </button>
                <a href="{{ route('submissions.index') }}" class="btn-sky-light max-w-fit">
                    {{ __('Clear Filters') }}
                </a>
            </div>
        </div>

    </form>
    <hr class="mb-3">

    {{-- index table --}}
    <div class="field-card mt-4 overflow-auto">
        <table class="table-dark">
            <caption></caption>
            <thead>
                <tr>
                    @foreach ($fields as $key => $value)
                        <th>
                            <div class="flex items-center gap-2">
                                <span class="text-nowrap">{{ __($value) }}</span>
                                <form action="{{ route('submissions.index') }}" method="GET">
                                    <input type="hidden" name="search" value="{{ request()->query('search') ?? '' }}">
                                    <input type="hidden" name="order_by" value="{{ $key }}">
                                    <input type="hidden" name="page" value="{{ request()->query('page') ?? 1 }}">
                                    <input type="hidden" name="order" value="{{ !empty(request()->query('order_by')) &&
                                        request()->query('order_by') == $key &&
                                        request()->query('order') == 'asc'
                                            ? 'desc'
                                            : 'asc' }}">
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
                            </div>
                        </th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="hover-row">
                @foreach ($submissions as $submission)
                @php
                    ds($submission);
                @endphp
                <tr>
                    @foreach ($fields as $key => $value)
                        @if (str_contains($key, '->'))
                            <td>
                                <a href="{{ route('submissions.view', $submission->id) }}">
                                    {{ App\Http\Services\ModelService::nestedValue($submission, $key) }}
                                </a>
                            </td>
                        @elseif ($key == 'submission_type')
                            <td>
                                <a href="{{ route('submissions.view', $submission->id) }}">
                                    {{ config('dropdowns.submission_types')[$submission->$key] }}
                                </a>
                            </td>
                        @elseif ($key == 'current_unit_number')
                            <td>
                                <a href="{{ route('submissions.view', $submission->id) }}">
                                    {{ $submission->$key . ' - ' .
                                    config('dropdowns.unit_numbers')[$submission->$key] }}
                                </a>
                            </td>
                        @else
                            <td>
                                <a href="{{ route('submissions.view', $submission->id) }}">
                                    {{ $submission->$key }}
                                </a>
                            </td>
                        @endif
                    @endforeach
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- pagination --}}
    {{ $submissions->appends([
        'search'              => request()->query('search') ?? '',
        'order_by'            => request()->query('order_by') ?? 'name',
        'order_direction'     => request()->query('order') ?? 'asc',
        'page'                => request()->query('page') ?? 1,
        'submission_type'     => request()->query('submission_type') ?? '-',
        'current_unit_number' => request()->query('current_unit_number') ?? '-',
    ])->links() }}
@endsection
