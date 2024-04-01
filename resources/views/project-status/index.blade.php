@extends('layouts.dashboard')

@section('dashboard-content')
    <div class="flex justify-between items-center">
        <h2 class="text-left">{{ __('Project statuses') }}</h2>
        <form action="{{ route('project-statuses.index') }}" method="get">
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
            <a href="{{ route('project-statuses.create') }}" class="btn btn-sky max-w-fit">
                {{ __('Add status') }}
            </a>
        </th>
    </div>

    {{-- table --}}
    <div class="field-card mt-4 overflow-auto">
        <table class="table-dark">
            <caption class="hidden">{{ __('Project statuses index table') }}</caption>
            {{-- headings --}}
            <thead>
                <tr>
                    <th class="text-nowrap">{{ __('Name') }}</th>
                </tr>
            </thead>
            {{-- rows --}}
            <tbody>
                @if (!$projectStatuses->isEmpty())
                    @foreach ($projectStatuses as $projectStatus)
                    <tr class="h-10">
                        <td class="max-w-[280px] truncate px-3">{{ $projectStatus->name }}</td>
                        {{-- edit and delete --}}
                        <td class="w-[150px]">
                            @if ($projectStatus->canEdit())
                                <div class="flex justify-end items-center gap-2">
                                    <a href="{{ route('project-statuses.edit', $projectStatus) }}"
                                    class="text-gray-300 hover:text-sky-700 max-w-fit">
                                        {{ __('Edit') }}
                                    </a>
                                    <div class="text-gray-300 hover:text-red-600 cursor-pointer max-w-fit"
                                    id="delete-button-{{ $projectStatus->id }}">
                                        {{ __('Delete') }}
                                    </div>
                                    @include('components.delete-modal', [
                                        'model' => $projectStatus,
                                        'route' => 'project-statuses',
                                        'method' => 'DELETE',
                                    ])
                                </div>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="13">{{ __('No statuses found') }}</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

    {{-- pagination --}}
    {{ $projectStatuses->appends([
        'search' => request()->query('search') ?? '',
    ])->links() }}
@endsection
