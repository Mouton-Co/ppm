@extends('layouts.dashboard')

@section('dashboard-content')
    {{-- cell form curtain --}}
    <div id="cell-curtain" class="absolute w-full h-full bg-transparent z-40 hidden"></div>

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

    {{-- index table --}}
    <div class="field-card mt-4 overflow-auto">
        <table class="table-dark">
            <caption class="hidden">{{ __('Parts index table') }}</caption>
            <thead>
                <tr>
                    @foreach (config('models.parts.columns') as $key => $field)
                        <th>
                            <span class="flex items-center gap-2">
                                {{ $field['name'] }}
                                <form action="{{ route('parts.index') }}" method="GET">
                                    <input type="hidden" name="search"
                                        value="{{ request()->query('search') ?? '' }}">
                                    <input type="hidden" name="order_by" value="{{ $key }}">
                                    <input type="hidden" name="page" value="{{ request()->query('page') ?? 1 }}">
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
                            @php
                                $editable = !empty($field['editable']) && $field['editable'];
                            @endphp
                            <td>
                                <span id="cell-{{ $cell }}"
                                class="relative w-full h-full
                                {{ $editable ? 'cell-edit hover:bg-sky-700 cursor-text hover:shadow-inner' : '' }}
                                {{ $key == 'date_stamp' ? 'min-w-[150px]' : '' }}">
                                    @php
                                        $value = str_contains($key, '->')
                                            ? App\Http\Services\ModelService::nestedValue($part, $key)
                                            : $part->$key;
                                    @endphp
                                    {{ $value ?? '-' }}

                                    {{-- editable field form --}}
                                    @if ($editable)
                                        <div class="absolute top-0 left-0 w-full h-full hidden cell-form
                                        bg-dark-field"
                                        id="cell-form-{{ $cell }}">
                                            <form action="{{ route('parts.update', $part->id) }}" method="post"
                                            class="relative w-full h-full">
                                                @csrf
                                                <input type="{{ $key == 'date_stamp' ? 'date' : 'text' }}"
                                                name="{{ $key }}" value="{{ $field['name'] }}"
                                                class="w-full h-full bg-transparent cell-form-input">
                                            </form>
                                        </div>
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
