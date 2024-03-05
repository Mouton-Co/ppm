@extends('layouts.dashboard')

@section('dashboard-content')

    {{-- title and search --}}
    <div class="flex justify-between mb-3">
        <h2>{{ __('Process types') }}</h2>
    </div>

    {{-- index table --}}
    <div class="field-card mt-4 overflow-auto no-scrollbar">
        <table class="table-dark no-scrollbar">
            <caption class="hidden">{{ __('Process types index table') }}</caption>
            <thead>
                <tr>
                    <th>
                        <span class="flex justify-between">
                            <div class="flex items-center">
                                <span class="text-nowrap">
                                    {{ __('Process type') }}
                                </span>
                            </div>
                        </span>
                    </th>
                    <th>
                        <span class="flex justify-between">
                            <div class="flex items-center">
                                <span class="text-nowrap">
                                    {{ __('Files required') }}
                                </span>
                            </div>
                        </span>
                    </th>
                    {{-- actions column --}}
                    <th class="w-[150px]">
                        <div class="w-full h-full flex justify-end items-center">
                            <a href="{{ route('process-types.create') }}"
                            class="text-gray-300 hover:text-sky-700 rounded-full p-1">
                                <x-icon.add class="h-6" />
                            </a>
                        </div>
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach ($processTypes as $processType)
                    <tr>
                        <td>
                            <span class="w-full h-full">
                                <div class="flex justify-start items-center">
                                    {{ $processType->process_type ?? 'N/A' }}
                                </div>
                            </span>
                        </td>
                        <td>
                            <span class="w-full h-full">
                                <div class="flex justify-start items-center">
                                    <ul class="list-disc pl-3">
                                        @foreach (explode(',', $processType->required_files) as $file)
                                            <li>
                                                @if (str_contains($file, '/'))
                                                    @php
                                                        list($file_1, $file_2) = explode('/', $file);
                                                    @endphp
                                                    <div class="flex items-center translate-y-1">
                                                        <img class="h-5 w-5 inline-block" alt="No image"
                                                        src="{{ asset('images/' . strtolower($file_1) . '.png') }}">
                                                        {{ $file_1 }}
                                                        <div class="px-2">OR</div>
                                                        <img class="h-5 w-5 inline-block" alt="No image"
                                                        src="{{ asset('images/' . strtolower($file_2) . '.png') }}">
                                                        {{ $file_2 }}
                                                    </div>
                                                @else
                                                    <img class="h-5 w-5 inline-block" alt="No image"
                                                    src="{{ asset('images/' . strtolower($file) . '.png') }}">
                                                    {{ $file }}
                                                @endif
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </span>
                        </td>
                        <td class="w-[150px]">
                            <div class="flex justify-end items-center gap-2">
                                <a href="{{ route('process-types.edit', $processType) }}"
                                class="text-gray-300 hover:text-sky-700 max-w-fit">
                                    {{ __('Edit') }}
                                </a>
                                <div class="text-gray-300 hover:text-red-600 cursor-pointer max-w-fit"
                                id="delete-button-{{ $processType->id }}">
                                    {{ __('Delete') }}
                                </div>
                                @include('components.delete-modal', [
                                    'model' => $processType,
                                    'route' => 'process-types',
                                    'method' => 'DELETE',
                                ])
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
