@extends('layouts.dashboard')

@section('dashboard-content')
    {{-- title and add button --}}
    <div class="flex justify-between items-center mb-2">
        <h2 class="text-left">{{ __('Projects') }}</h2>
        <a href="{{ route('projects.create') }}" class="btn btn-sky max-w-fit">
            {{ __('Add project') }}
        </a>
    </div>

    {{-- filters --}}
    <hr>
    <form action="{{ route('projects.index') }}" method="get">
        <div class="grid grid-cols-1 sm:grid-cols-2 2xl:grid-cols-4 gap-2 my-2 items-center justify-start">

            {{-- machine_nr --}}
            <div class="flex items-center justify-start gap-2 w-full">
                <label for="search" class="min-w-fit text-white text-nowrap">
                    {{ __('Machine #') }}
                </label>
                <input
                    type="text"
                    name="machine_nr"
                    placeholder="Machine #"
                    value="{{ request()->query('machine_nr') ?? '' }}"
                    class="field-dark w-full"
                >
            </div>

            {{-- country --}}
            <div class="flex items-center justify-start gap-2 w-full">
                <label for="search" class="min-w-fit text-white text-nowrap">
                    {{ __('Country') }}
                </label>
                <input
                    type="text"
                    name="country"
                    placeholder="Country..."
                    value="{{ request()->query('country') ?? '' }}"
                    class="field-dark w-full"
                >
            </div>

            {{-- currently_responsible --}}
            <div class="flex items-center justify-start gap-2">
                <label for="currently_responsible" class="min-w-fit text-white text-nowrap">
                    {{ __('Currently responsible') }}
                </label>
                <select name="currently_responsible" class="field bg-transparent border-none
                !ring-0 focus:ring-0 focus:outline-none cursor-pointer">
                    <option value="" selected>{{ __('All') }}</option>
                    @foreach (\App\Models\ProjectResponsible::orderBy('name')->get() as $responsible)
                        <option value="{{ $responsible->name }}"
                        @if (
                            !empty(request()->query('currently_responsible')) &&
                            request()->query('currently_responsible') == $responsible->name
                        ) selected @endif>
                            {{ $responsible->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- status --}}
            <div class="flex items-center justify-start gap-2">
                <label for="status" class="min-w-fit text-white text-nowrap">
                    {{ __('Status') }}
                </label>
                <select name="status" class="field bg-transparent border-none
                !ring-0 focus:ring-0 focus:outline-none cursor-pointer">
                    <option value="" selected>{{ __('All') }}</option>
                    @foreach (\App\Models\ProjectStatus::orderBy('name')->get() as $status)
                        <option value="{{ $status->name }}"
                        @if (
                            !empty(request()->query('status')) &&
                            request()->query('status') == $status->name
                        ) selected @endif>
                            {{ $status->name }}
                        </option>
                    @endforeach
                </select>
            </div>

        </div>

        <div class="w-full flex justify-end gap-2">
            <input type="hidden" name="page" value="{{ request()->query('page') ?? 1 }}">
            <button type="submit" class="btn-sky max-w-fit">
                {{ __('Filter') }}
            </button>
            <a href="{{ route('projects.index') }}" class="btn-sky-light max-w-fit">
                {{ __('Clear Filters') }}
            </a>
        </div>
    </form>

    {{-- index table --}}
    <div class="field-card mt-4 overflow-auto no-scrollbar">
        <table class="table-dark no-scrollbar">
            <caption class="hidden">{{ __('Projects index table') }}</caption>
            <thead>
                <tr>
                    <th></th>
                    @foreach (config('models.projects.columns') as $key => $field)
                        <th>
                            <span class="flex justify-between">
                                <div class="flex items-center gap-2">
                                    <span class="text-nowrap">
                                        {{ $field['name'] }}
                                    </span>
                                    @if ($field['sortable'])
                                        <form action="{{ route('projects.index') }}" method="GET">
                                            <input type="hidden" name="order_by" value="{{ $key }}">
                                            <input type="hidden" name="page"
                                                value="{{ request()->query('page') ?? 1 }}">
                                            <input type="hidden" name="order"
                                                value="{{ !empty(request()->query('order_by')) &&
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
                                    @endif
                                </div>
                            </span>
                        </th>
                    @endforeach
                    <th></th>
                </tr>
            </thead>
            <tbody class="hover-cell">
                @php
                    $cell = 0;
                @endphp
                @foreach ($projects as $project)
                    <tr>
                        {{-- edit --}}
                        <td class="w-[150px]">
                            <div class="flex justify-end items-center gap-2">
                                <a href="{{ route('projects.edit', $project) }}"
                                class="text-gray-300 hover:text-sky-700 max-w-fit">
                                    {{ __('Edit') }}
                                </a>
                            </div>
                        </td>
                        @foreach (config('models.projects.columns') as $key => $field)
                            <td class="max-w-[280px] truncate">
                                @php
                                    $editable = !empty($field['editable']) && $field['editable'];
                                    $type = !empty($field['type']) ? $field['type'] : 'text';
                                @endphp
                                <span id="{{ $project->id . '-' . $key }}" class="relative w-full h-full
                                {{ $editable && $type == 'text' ?
                                'cell-edit hover:bg-sky-700 cursor-text hover:shadow-inner' : '' }}">

                                    @php
                                        $value = str_contains($key, '->')
                                            ? App\Http\Services\ModelService::nestedValue($project, $key)
                                            : $project->$key;
                                    @endphp

                                    @if ($editable)
                                        @switch($type)
                                            @case('text')
                                                <input type="{{ $key == 'date_stamp' ? 'date' : 'text' }}"
                                                    name="{{ $key }}" value="{{ $value }}"
                                                    class="w-auto h-full bg-transparent border-none
                                                    focus:ring-0 focus:outline-none cell-text tooltip-trigger"
                                                    item-id="{{ $project->id }}"
                                                    @if ($key == 'noticed_issue' || $key == 'proposed_solution')
                                                        tooltip-id="{{ $project->id . '-' . $key . '-tooltip' }}"
                                                    @endif>

                                                @if ($key == 'noticed_issue' || $key == 'proposed_solution')
                                                    <x-tooltip id="{{ $project->id . '-' . $key . '-tooltip' }}">
                                                        {!! $value !!}
                                                    </x-tooltip>
                                                @endif
                                                @break
                                            @case('select')
                                                @php
                                                    $options = $field['options']['model']::orderBy('name')->get();
                                                @endphp
                                                <select name="{{ $key }}" class="field bg-transparent border-none
                                                !ring-0 !w-[195px] focus:ring-0 focus:outline-none cursor-pointer
                                                cell-dropdown" item-id="{{ $project->id }}">
                                                    <option value="" disabled selected>
                                                        {{ $value ?? '' }}
                                                    </option>
                                                    @foreach ($options as $option)
                                                        <option
                                                            value="{{ $option->name }}"
                                                            @if ($option->name === $project->$value) selected @endif
                                                        >
                                                            {{ $option->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @break
                                        @endswitch
                                    @else
                                        @if ($key == 'submission_id')
                                                @if (! empty($project->submission->submission_code))
                                                    {{ $project->submission->submission_code }}
                                                @else
                                                <div class="w-full flex justify-start">
                                                    <a class="btn btn-sky max-w-fit !text-xs !py-1 !max-h-fit !rounded"
                                                    target="_blank" href="{{ route('new.submission', [
                                                        'machine_number' => $project->machine_nr,
                                                        'submission_type' => 'additional_project',
                                                        'project_id' => $project->id,
                                                        'notes' => $project->notes,
                                                    ]) }}">
                                                        {{ __('Link submission') }}
                                                    </a>
                                                </div>
                                                @endif
                                        @else
                                            {{ $value ?? '-' }}
                                        @endif
                                    @endif

                                </span>
                            </td>
                            @php
                                $cell++;
                            @endphp
                        @endforeach
                        {{-- edit and delete --}}
                        <td class="w-[150px]">
                            <div class="flex justify-end items-center gap-2">
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
            </tbody>
        </table>
    </div>

    {{-- pagination --}}
    {{ $projects->appends([
        'order_by' => request()->query('order_by'),
        'order' => request()->query('order'),
    ])->links() }}
@endsection
