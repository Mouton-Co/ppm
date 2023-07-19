@extends('layouts.dashboard')

@section('dashboard-content')

    <div class="field-card mt-5 text-white">
        <dl class="divide-y divide-white/10">
            <h2 class="pb-6">{{ __('Submission details') }}</h2>
            <div class="details-list">
                <dt class="details-list-title">{{ __('Assembly name') }}</dt>
                <dd class="details-list-value">{{ $submission->assembly_name ?? 'N/A' }}</dd>
            </div>
            <div class="details-list">
                <dt class="details-list-title">{{ __('Designer') }}</dt>
                <dd class="details-list-value">{{ $submission->user->name ?? 'N/A' }}</dd>
            </div>
            <div class="details-list">
                <dt class="details-list-title">{{ __('Submission code') }}</dt>
                <dd class="details-list-value">{{ $submission->submission_code ?? 'N/A' }}</dd>
            </div>
            <div class="details-list">
                <dt class="details-list-title">{{ __('Machine number') }}</dt>
                <dd class="details-list-value">{{ $submission->machine_number ?? 'N/A' }}</dd>
            </div>
            <div class="details-list">
                <dt class="details-list-title">{{ __('Type of submission') }}</dt>
                <dd class="details-list-value">
                    {{ config('dropdowns.submission_types.' . $submission->submission_type) ?? 'N/A' }}
                </dd>
            </div>
            <div class="details-list">
                <dt class="details-list-title">{{ __('Current unit number') }}</dt>
                <dd class="details-list-value">
                    {{ $submission->current_unit_number .
                        ' - ' .
                        config('dropdowns.unit_numbers.' . $submission->current_unit_number) ?? 'N/A' }}
                </dd>
            </div>
            <div class="details-list">
                <dt class="details-list-title">{{ __('Notes') }}</dt>
                <dd class="details-list-value">{{ $submission->notes ?? '' }}</dd>
            </div>
        </dl>
    </div>

    <div class="field-card mt-10 text-white">
        <dl class="divide-y divide-dark-field-border">
            <h2 class="pb-6">{{ __('Submission parts') }}</h2>

            @foreach ($submission->parts as $part)
                <div id="part-name-{{ $part->id }}" class="details-list hover:bg-sky-700 children-white
                    cursor-pointer pl-1 sm:pl-1 md:pl-1">
                    <dt class="details-list-title flex items-center gap-1">
                        <svg id="arrow-down-{{ $part->id }}" class="w-4 aspect-square" viewBox="0 0 24 24" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                            <g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier"
                            stroke-linecap="round" stroke-linejoin="round"></g>
                            <g id="SVGRepo_iconCarrier"> <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M12.7071 14.7071C12.3166 15.0976 11.6834 15.0976 11.2929 14.7071L6.29289
                                9.70711C5.90237 9.31658 5.90237 8.68342 6.29289 8.29289C6.68342 7.90237
                                7.31658 7.90237 7.70711 8.29289L12 12.5858L16.2929 8.29289C16.6834 7.90237
                                17.3166 7.90237 17.7071 8.29289C18.0976 8.68342 18.0976 9.31658 17.7071
                                9.70711L12.7071 14.7071Z" fill="currentColor"></path>
                            </g>
                        </svg>
                        <svg id="arrow-up-{{ $part->id }}" class="hidden w-4 aspect-square" viewBox="0 0 24 24"
                        fill="none" xmlns="http://www.w3.org/2000/svg" transform="rotate(180)">
                            <g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier"
                            stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M12.7071 14.7071C12.3166 15.0976
                                11.6834 15.0976 11.2929 14.7071L6.29289 9.70711C5.90237 9.31658 5.90237
                                8.68342 6.29289 8.29289C6.68342 7.90237 7.31658 7.90237 7.70711
                                8.29289L12 12.5858L16.2929 8.29289C16.6834 7.90237 17.3166 7.90237
                                17.7071 8.29289C18.0976 8.68342 18.0976 9.31658 17.7071 9.70711L12.7071
                                14.7071Z" fill="currentColor"></path>
                            </g>
                        </svg>
                        {{ __('Part') }}
                    </dt>
                    <dd class="details-list-value">{{ $part->name ?? 'N/A' }}</dd>
                </div>
                <div id="part-info-{{ $part->id }}" aria-expanded="false"
                    class="divide-y divide-dark-field-border hidden parts-info-closed">
                    <div class="details-list pl-4 sm:pl-4 md:pl-4">
                        <dt class="details-list-title">{{ __('Quantity') }}</dt>
                        <dd class="details-list-value">{{ $part->quantity ?? 'N/A' }}</dd>
                    </div>
                    <div class="details-list pl-4 sm:pl-4 md:pl-4">
                        <dt class="details-list-title">{{ __('Material') }}</dt>
                        <dd class="details-list-value">{{ $part->material ?? 'N/A' }}</dd>
                    </div>
                    <div class="details-list pl-4 sm:pl-4 md:pl-4">
                        <dt class="details-list-title">{{ __('Material thickness') }}</dt>
                        <dd class="details-list-value">{{ $part->material_thickness ?? 'N/A' }}</dd>
                    </div>
                    <div class="details-list pl-4 sm:pl-4 md:pl-4">
                        <dt class="details-list-title">{{ __('Finish') }}</dt>
                        <dd class="details-list-value">{{ $part->finish ?? 'N/A' }}</dd>
                    </div>
                    <div class="details-list pl-4 sm:pl-4 md:pl-4">
                        <dt class="details-list-title">{{ __('Used in weldment') }}</dt>
                        <dd class="details-list-value">{{ $part->used_in_weldment ?? 'N/A' }}</dd>
                    </div>
                    <div class="details-list pl-4 sm:pl-4 md:pl-4">
                        <dt class="details-list-title">{{ __('Process type') }}</dt>
                        <dd class="details-list-value">{{ $part->process_type ?? 'N/A' }}</dd>
                    </div>
                    <div class="details-list pl-4 sm:pl-4 md:pl-4">
                        <dt class="details-list-title">{{ __('Manufactured or purchased') }}</dt>
                        <dd class="details-list-value">{{ $part->manufactured_or_purchased ?? 'N/A' }}</dd>
                    </div>
                    <div class="details-list pl-4 sm:pl-4 md:pl-4">
                        <dt class="details-list-title">{{ __('Notes') }}</dt>
                        <dd class="details-list-value">{{ $part->notes ?? '' }}</dd>
                    </div>
    
                    @if (count($part->files))
                        <div class="details-list pl-4 sm:pl-4 md:pl-4 grid-33-66 sm:gap-2 md:gap-2">
                            <dt class="details-list-title">{{ __('Files') }}</dt>
                            <ul role="list" class="details-list-files my-3">
                                @foreach ($part->files as $file)
                                    <li class="details-list-file">
                                        <div class="flex w-0 flex-1 items-center">
                                            <svg class="h-5 w-5 flex-shrink-0 text-gray-400" viewBox="0 0 20 20"
                                            fill="currentColor" aria-hidden="true">
                                                <path fill-rule="evenodd" d="M15.621 4.379a3 3 0 00-4.242 0l-7 7a3 3 0
                                                004.241 4.243h.001l.497-.5a.75.75 0 011.064 1.057l-.498.501-.002.002a4.5
                                                4.5 0 01-6.364-6.364l7-7a4.5 4.5 0 016.368 6.36l-3.455 3.553A2.625
                                                2.625 0 119.52 9.52l3.45-3.451a.75.75 0 111.061 1.06l-3.45 3.451a1.125
                                                1.125 0 001.587 1.595l3.454-3.553a3 3 0 000-4.242z"
                                                clip-rule="evenodd"/>
                                            </svg>
                                            <div class="ml-4 flex min-w-0 flex-1 gap-2">
                                                <span class="truncate font-medium">
                                                    {{ $file->name.'.'.$file->file_type }}
                                                </span>
                                                <span class="hidden sm:flex md:flex pl-4 flex-shrink-0 text-gray-400">
                                                    {{ $file->size }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="ml-4 flex-shrink-0">
                                            <form class="text-sky-700" action="{{ route('file.download', $file->id) }}">
                                                <input class="font-medium text-sky-700 hover:text-sky-600
                                                cursor-pointer" type="submit" value="Download">
                                            </form>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
            @endforeach
        </dl>
    </div>

@endsection
