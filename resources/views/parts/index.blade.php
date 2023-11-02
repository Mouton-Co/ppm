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

    {{-- index table --}}
    <div class="field-card mt-4 overflow-auto">
        <table class="table-dark">
            <caption class="hidden">{{ __('Parts index table') }}</caption>
            <thead>
                <tr>
                    @foreach (config('models.parts.columns') as $field => $column)
                        <th>
                            <span class="flex items-center gap-2">
                                {{ $column }}
                                <form action="{{ route('parts.index') }}" method="GET">
                                    <input type="hidden" name="search"
                                        value="{{ request()->query('search') ?? '' }}">
                                    <input type="hidden" name="order_by" value="{{ $field }}">
                                    <input type="hidden" name="page" value="{{ request()->query('page') ?? 1 }}">
                                    <input type="hidden" name="order_direction"
                                        value="{{ !empty(request()->query('order_by')) &&
                                        request()->query('order_by') == $field &&
                                        request()->query('order_direction') == 'asc'
                                            ? 'desc'
                                            : 'asc' }}">
                                    <button type="submit">
                                        <x-icon.up-arrow
                                            class="cursor-pointer h-[10px]
                                            {{ !empty(request()->query('order_by')) &&
                                            request()->query('order_by') == $field &&
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
                @foreach ($parts as $part)
                    <tr>
                        @foreach (config('models.parts.columns') as $field => $column)
                            @if (str_contains($field, '->'))
                                <td>
                                    <span>
                                        {{ App\Http\Services\ModelService::nestedValue($part, $field) }}
                                    </span>
                                </td>
                            @else
                                <td>
                                    <span>
                                        {{ $part->$field }}
                                    </span>
                                </td>
                            @endif
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
