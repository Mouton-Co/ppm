@extends('layouts.dashboard')

@section('dashboard-content')

    <a href="{{ route('submissions.index') }}" class="btn btn-sky max-w-fit mb-5">
        <svg class="w-4 mr-2 aspect-square" viewBox="0 0 1024 1024" xmlns="http://www.w3.org/2000/svg"
        fill="currentColor">
            <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
            <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
            <g id="SVGRepo_iconCarrier">
                <path fill="currentColor" d="M224 480h640a32 32 0 1 1 0 64H224a32 32 0 0 1 0-64z"></path>
                <path fill="currentColor" d="m237.248 512 265.408 265.344a32 32 0 0 1-45.312 45.312l-288-288a32 32
                0 0 1 0-45.312l288-288a32 32 0 1 1 45.312 45.312L237.248 512z"></path>
            </g>
        </svg>
        {{ __('All submission') }}
    </a>

    <div class="field-card mt-5 text-white">
        <dl class="divide-y divide-white/10">
            <div class="flex justify-between items-center pb-5">
                <h2 class="text-left">{{ __('Submission details') }}</h2>
                <a href="{{ route('zip.download', $submission->submission_code) }}"
                    class="btn btn-sky max-h-fit max-w-fit" download>
                    <span class="hidden md:block mr-2">{{ __('Download') }}</span>
                    <svg class="h-6" version="1.1" id="_x32_" xmlns="http://www.w3.org/2000/svg"
                    xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 512 512" xml:space="preserve"
                    fill="currentColor">
                        <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                        <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                        <g id="SVGRepo_iconCarrier">
                            <style type="text/css">.st0{fill:currentColor;}</style>
                            <g>
                                <path class="st0" d="M378.409,0H208.294h-13.176l-9.314,9.314L57.016,138.102l-9.314,9.314v13.176v265.514
                                c0,47.36,38.528,85.895,85.895,85.895h244.811c47.36,0,85.889-38.535,85.889-85.895V85.896C464.298,38.528,425.769,0,378.409,0z
                                M432.493,426.105c0,29.877-24.214,54.091-54.084,54.091H133.598c-29.877,0-54.091-24.214-54.091-54.091V160.591h83.717
                                c24.885,0,45.07-20.178,45.07-45.07V31.804h170.115c29.87,0,54.084,24.214,54.084,54.092V426.105z"></path>
                                <path class="st0" d="M207.466,276.147c4.755-6.926,5.841-9.782,5.841-13.853c0-5.166-3.534-9.509-10.46-9.509h-43.464
                                c-5.977,0-9.506,3.805-9.506,8.965c0,5.159,3.529,8.825,9.506,8.825h29.339v0.272l-33.958,50.119
                                c-4.615,6.661-6.382,9.915-6.382,14.669c0,5.167,3.666,9.51,10.46,9.51h44.959c6.109,0,9.506-3.666,9.506-8.826
                                c0-5.166-3.397-8.965-9.506-8.965h-30.834v-0.272L207.466,276.147z"></path>
                                <path class="st0" d="M247.684,251.968c-5.841,0-10.051,4.21-10.051,10.592v72.804c0,6.388,4.21,10.599,10.051,10.599
                                c5.704,0,9.915-4.21,9.915-10.599V262.56C257.599,256.178,253.388,251.968,247.684,251.968z"></path>
                                <path class="st0" d="M323.344,252.785h-28.523c-5.432,0-8.693,3.533-8.693,8.825v73.754c0,6.388,4.21,10.599,10.051,10.599
                                c5.704,0,9.914-4.21,9.914-10.599v-22.406c0-0.545,0.272-0.817,0.817-0.817h16.433c20.102,0,32.192-12.226,32.192-29.612
                                C355.535,264.871,343.582,252.785,323.344,252.785z M322.122,294.888h-15.211c-0.545,0-0.817-0.272-0.817-0.81v-23.23
                                c0-0.545,0.272-0.816,0.817-0.816h15.211c8.42,0,13.448,5.027,13.448,12.498C335.569,290,330.542,294.888,322.122,294.888z"></path>
                            </g>
                        </g>
                    </svg>
                </a>
            </div>
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

    <div class="field-card mt-5 text-white">
        <dl class="divide-y divide-dark-field-border">
            <h2 class="pb-6 text-left">{{ __('Submission parts') }}</h2>
            
            @foreach ($submission->parts as $part)
                <div id="part-name-{{ $part->id }}" class="details-list hover:bg-sky-700 children-white
                    cursor-pointer pl-1 sm:pl-1 md:pl-1 {{ app('request')->input('part') == $part->id ?
                    'bg-sky-700' : ''}}">
                    <dt class="details-list-title flex items-center gap-1">
                        <svg id="arrow-right-{{ $part->id }}" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg"
                            class="w-5 aspect-square {{ app('request')->input('part') == $part->id
                            ? 'hidden' : '' }}">
                            <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                            <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                            <g id="SVGRepo_iconCarrier"> <path d="M10.25 16.25C10.1493 16.2466 10.0503 16.2227
                                9.95921 16.1797C9.86807 16.1367 9.78668 16.0756 9.72001 16C9.57956 15.8594 9.50067
                                15.6688 9.50067 15.47C9.50067 15.2713 9.57956 15.0806 9.72001 14.94L12.72
                                11.94L9.72001 8.94002C9.66069 8.79601 9.64767 8.63711 9.68277 8.48536C9.71786
                                8.33361 9.79933 8.19656 9.91586 8.09322C10.0324 7.98988 10.1782 7.92538 10.3331
                                7.90868C10.4879 7.89198 10.6441 7.92391 10.78 8.00002L14.28 11.5C14.4205 11.6407
                                14.4994 11.8313 14.4994 12.03C14.4994 12.2288 14.4205 12.4194 14.28 12.56L10.78
                                16C10.7133 16.0756 10.6319 16.1367 10.5408 16.1797C10.4497 16.2227 10.3507 16.2466
                                10.25 16.25Z" fill="currentColor"></path>
                            </g>
                        </svg>
                        <svg id="arrow-up-{{ $part->id }}" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg" transform="rotate(90)"
                            class="w-5 aspect-square {{ app('request')->input('part') == $part->id
                                ? '' : 'hidden' }}">
                            <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                            <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                            <g id="SVGRepo_iconCarrier"> <path d="M10.25 16.25C10.1493 16.2466 10.0503 16.2227 9.95921
                                16.1797C9.86807 16.1367 9.78668 16.0756 9.72001 16C9.57956 15.8594 9.50067 15.6688
                                9.50067 15.47C9.50067 15.2713 9.57956 15.0806 9.72001 14.94L12.72 11.94L9.72001
                                8.94002C9.66069 8.79601 9.64767 8.63711 9.68277 8.48536C9.71786 8.33361 9.79933
                                8.19656 9.91586 8.09322C10.0324 7.98988 10.1782 7.92538 10.3331 7.90868C10.4879
                                7.89198 10.6441 7.92391 10.78 8.00002L14.28 11.5C14.4205 11.6407 14.4994 11.8313
                                14.4994 12.03C14.4994 12.2288 14.4205 12.4194 14.28 12.56L10.78 16C10.7133 16.0756
                                10.6319 16.1367 10.5408 16.1797C10.4497 16.2227 10.3507 16.2466 10.25 16.25Z"
                                fill="currentColor"></path>
                            </g>
                        </svg>
                        {{ __('Part') }}
                    </dt>
                    <dd class="details-list-value {{ app('request')->input('part') == $part->id ?
                        '!text-white' : ''}}">
                        {{ $part->name ?? 'N/A' }}
                    </dd>
                </div>
                <div id="part-info-{{ $part->id }}"
                    aria-expanded="{{ app('request')->input('part') == $part->id ? 'true' : 'false' }}"
                    class="divide-y divide-dark-field-border {{ app('request')->input('part') == $part->id
                    ? 'parts-info-expanded' : 'hidden parts-info-closed' }}">
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

    <script type="text/javascript">
        document.addEventListener("DOMContentLoaded", () => {
            console.log("#<?= 'part-name-' . app('request')->input('part') ?>");
            document.querySelector("#<?= 'part-name-' . app('request')->input('part') ?>").scrollIntoView({
                behavior: 'smooth'
            });
        });
    </script>

@endsection
