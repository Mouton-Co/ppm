@extends('layouts.dashboard')

@section('dashboard-content')
    <div class="flex justify-between items-center">
        <h2 class="text-left">{{ __('Email triggers') }}</h2>
        <form action="{{ route('recipient-groups.index') }}" method="get">
            {{-- search --}}
            <div class="flex items-center justify-start gap-2 smaller-than-711:flex-col smaller-than-711:items-start">
                <input type="text" name="search" placeholder="Search..." value="{{ request()->query('search') ?? '' }}"
                    class="field-dark min-w-[500px]">
            </div>
        </form>
    </div>

    {{-- add button --}}
    <div class="flex justify-end items-center mt-4">
        <th class="flex justify-end w-48">
            <a href="{{ route('recipient-groups.create') }}" class="btn btn-sky max-w-fit">
                {{ __('Add trigger') }}
        </a>
        </th>
    </div>

    {{-- table --}}
    <div class="field-card mt-4 overflow-auto">
        <table class="table-dark">
            <caption class="hidden">{{ __('Group recipients index table') }}</caption>
            {{-- headings --}}
            <thead>
                <tr>
                    <th class="text-nowrap">{{ __('Triggers when...') }}</th>
                    <th class="text-nowrap">{{ __('Changed to... / Item...') }}</th>
                    <th class="text-nowrap">{{ __('Recipients') }}</th>
                </tr>
            </thead>
            {{-- rows --}}
            <tbody>
                @if (!$recipientGroups->isEmpty())
                    @foreach ($recipientGroups as $recipientGroup)
                    <tr class="h-10">
                        <td class="max-w-[280px] truncate px-3">{{ $recipientGroup->field }}</td>
                        <td class="max-w-[280px] truncate px-3">{{ $recipientGroup->value }}</td>
                        <td class="max-w-[280px] truncate px-3 py-1">
                            {!! nl2br($recipientGroup->recipients) !!}
                        </td>
                        {{-- edit and delete --}}
                        <td class="w-[150px]">
                            <div class="flex justify-end items-center gap-2">
                                <a href="{{ route('recipient-groups.edit', $recipientGroup) }}"
                                class="text-gray-300 hover:text-sky-700 max-w-fit">
                                    {{ __('Edit') }}
                                </a>

                                <div class="text-gray-300 hover:text-red-600 cursor-pointer max-w-fit"
                                id="delete-button-{{ $recipientGroup->id }}">
                                    {{ __('Delete') }}
                                </div>
                                @include('components.delete-modal', [
                                    'model' => $recipientGroup,
                                    'route' => 'recipient-groups',
                                    'method' => 'DELETE',
                                ])
                            </div>
                        </td>
                    </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="13">{{ __('No recipient groups found') }}</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

    {{-- pagination --}}
    {{ $recipientGroups->appends([
        'search' => request()->query('search') ?? '',
    ])->links() }}
@endsection
