@extends('layouts.dashboard')

@section('dashboard-content')
    <div class="flex justify-between items-center">
        <h2 class="text-left">{{ __('Projects') }}</h2>
        <form action="{{ route('projects.index') }}" method="get">
            {{-- search --}}
            <div class="flex items-center justify-start gap-2 smaller-than-711:flex-col smaller-than-711:items-start">
                <input type="text" name="search" placeholder="Search..." value="{{ request()->query('search') ?? '' }}"
                    class="field-dark min-w-[500px]">
            </div>
        </form>
    </div>

    <div class="flex justify-end items-center mt-4">
        <th class="flex justify-end w-48">
            <a href="{{ route('projects.create') }}" class="btn btn-sky max-w-fit">
                {{ __('Add project') }}
            </a>
        </th>
    </div>

    <div class="field-card mt-4 overflow-auto">
        <table class="table-dark">
            <caption class="hidden">{{ __('Projects index table') }}</caption>
            <thead>
                <tr>
                    <th class="text-nowrap">{{ __('Machine Nr') }}</th>
                    <th class="text-nowrap">{{ __('Country') }}</th>
                    <th class="text-nowrap">{{ __('COC') }}</th>
                    <th class="text-nowrap">{{ __('Noticed Issue') }}</th>
                    <th class="text-nowrap">{{ __('Proposed Solution') }}</th>
                    <th class="text-nowrap">{{ __('Currently Responsible') }}</th>
                    <th class="text-nowrap">{{ __('Status') }}</th>
                    <th class="text-nowrap">{{ __('Resolved At') }}</th>
                    <th class="text-nowrap">{{ __('Related PO') }}</th>
                    <th class="text-nowrap">{{ __('Customer Comment') }}</th>
                    <th class="text-nowrap">{{ __('Commisioner Comment') }}</th>
                    <th class="text-nowrap">{{ __('Logistics Comment') }}</th>
                    <th class="text-nowrap">{{ __('Submission ID') }}</th>
                </tr>
            </thead>
            <tbody>
                @if (!$projects->isEmpty())
                    @foreach ($projects as $project)
                    <tr class="h-10">
                        <td class="max-w-[280px] truncate px-3">{{ $project->machine_nr }}</td>
                        <td class="max-w-[280px] truncate px-3">{{ $project->country }}</td>
                        <td class="max-w-[280px] truncate px-3">{{ $project->coc }}</td>
                        <td class="max-w-[280px] truncate px-3">{{ $project->noticed_issue }}</td>
                        <td class="max-w-[280px] truncate px-3">{{ $project->proposed_solution }}</td>
                        <td class="max-w-[280px] truncate px-3">{{ $project->currently_responsible }}</td>
                        <td class="max-w-[280px] truncate px-3">{{ $project->status }}</td>
                        <td class="max-w-[280px] truncate px-3">{{ $project->resolved_at }}</td>
                        <td class="max-w-[280px] truncate px-3">{{ $project->related_po }}</td>
                        <td class="max-w-[280px] truncate px-3">{{ $project->customer_comment }}</td>
                        <td class="max-w-[280px] truncate px-3">{{ $project->commisioner_comment }}</td>
                        <td class="max-w-[280px] truncate px-3">{{ $project->logistics_comment }}</td>
                        <td class="max-w-[280px] truncate px-3">
                            {{ $project->submission->submission_code ?? 'N/A' }}
                        </td>
                        <td class="w-[150px]">
                            <div class="flex justify-end items-center gap-2">
                                <a href="{{ route('projects.edit', $project) }}"
                                class="text-gray-300 hover:text-sky-700 max-w-fit">
                                    {{ __('Edit') }}
                                </a>
                                <div class="text-gray-300 hover:text-red-600 cursor-pointer max-w-fit"
                                id="delete-button-{{ $project->id }}">
                                    {{ __('Delete') }}
                                </div>
                                @include('components.delete-modal', [
                                    'model' => $project,
                                    'route' => 'projects',
                                    'method' => 'DELETE',
                                ])
                            </div>
                        </td>
                    </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="13">{{ __('No projects found') }}</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

    {{-- pagination --}}
    {{ $projects->appends([
        'search' => request()->query('search') ?? '',
    ])->links() }}
@endsection
