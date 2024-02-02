@extends('layouts.dashboard')

@section('dashboard-content')
    <div class="flex justify-between items-center">
        <h2 class="text-left">{{ __('Representatives') }}</h2>
        <form action="{{ route('representatives.index') }}" method="get">
            {{-- search --}}
            <div class="flex items-center justify-start gap-2 smaller-than-711:flex-col smaller-than-711:items-start">
                <input type="text" name="search" placeholder="Search..." value="{{ request()->query('search') ?? '' }}"
                    class="field-dark min-w-[500px]">
            </div>
        </form>
    </div>

    <div class="field-card mt-4 overflow-auto">
        <table class="table-dark">
            <caption class="hidden">{{ __('Representatives index table') }}</caption>
            <thead>
                <tr>
                    <th>{{ __('Name') }}</th>
                    <th>{{ __('Email') }}</th>
                    <th>{{ __('Phone 1') }}</th>
                    <th>{{ __('Phone 2') }}</th>
                    <th>{{ __('Supplier') }}</th>
                    <th class="flex justify-end w-48">
                        <a href="{{ route('representatives.create') }}" class="btn btn-sky max-w-fit">
                            {{ __('Add representative') }}
                        </a>
                    </th>
                </tr>
            </thead>
            <tbody class="hover-row">
                @foreach ($representatives as $representative)
                <tr>
                    <td>
                        <a href="{{ route('representatives.edit', $representative->id) }}">
                            {{ $representative->name }}
                        </a>
                    </td>
                    <td>
                        <a href="{{ route('representatives.edit', $representative->id) }}">
                            {{ $representative->email ?? 'N/A' }}
                        </a>
                    </td>
                    <td>
                        <a href="{{ route('representatives.edit', $representative->id) }}">
                            {{ $representative->phone_1 ?? 'N/A' }}
                        </a>
                    </td>
                    <td>
                        <a href="{{ route('representatives.edit', $representative->id) }}">
                            {{ $representative->phone_2 ?? 'N/A' }}
                        </a>
                    </td>
                    <td>
                        <a href="{{ route('representatives.edit', $representative->id) }}">
                            {{ $representative->supplier->name ?? 'N/A' }}
                        </a>
                    </td>
                    <td class="w-48">
                        <svg class="h-5 w-5 ml-auto !cursor-pointer" viewBox="0 0 20 20"
                        fill="currentColor" aria-hidden="true" id="delete-button-{{ $representative->id }}">
                            <path d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0
                            101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75
                            0 00-1.06-1.06L10 8.94 6.28 5.22z"/>
                        </svg>
                        @include('components.delete-modal', [
                            'model'   => $representative,
                            'route'   => 'representatives',
                            'method'  => 'DELETE',
                        ])
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- pagination --}}
    {{ $representatives->appends([
        'search' => request()->query('search') ?? '',
    ])->links() }}
@endsection
