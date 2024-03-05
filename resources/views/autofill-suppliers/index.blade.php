@extends('layouts.dashboard')

@section('dashboard-content')

    {{-- title and search --}}
    <div class="flex justify-between mb-3">
        <h2>{{ __('Autofill Suppliers') }}</h2>
    </div>

    {{-- index table --}}
    <div class="field-card mt-4 overflow-auto no-scrollbar">
        <table class="table-dark no-scrollbar">
            <caption class="hidden">{{ __('Autofill suppliers index table') }}</caption>
            <thead>
                <tr>
                    <th>
                        <span class="flex justify-between">
                            <div class="flex items-center gap-2">
                                <span class="text-nowrap">
                                    {{ __('If part contains...') }}
                                </span>
                            </div>
                        </span>
                    </th>
                    <th>
                        <span class="flex justify-between">
                            <div class="flex items-center gap-2">
                                <span class="text-nowrap">
                                    {{ __('Autofill to supplier...') }}
                                </span>
                            </div>
                        </span>
                    </th>
                    {{-- actions column --}}
                    <th class="w-[150px]">
                        <div class="w-full h-full flex justify-end items-center">
                            <a href="{{ route('autofill-suppliers.create') }}"
                            class="text-gray-300 hover:text-sky-700 rounded-full p-1">
                                <x-icon.add class="h-6" />
                            </a>
                        </div>
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach ($autofillSuppliers as $autofillSupplier)
                    <tr>
                        <td>
                            <span class="w-full h-full">
                                <div class="flex justify-start items-center gap-2">
                                    {{ $autofillSupplier->text ?? 'N/A' }}
                                </div>
                            </span>
                        </td>
                        <td>
                            <span class="w-full h-full">
                                <div class="flex justify-start items-center gap-2">
                                    {{ $autofillSupplier->supplier->name ?? 'N/A' }}
                                </div>
                            </span>
                        </td>
                        <td class="w-[150px]">
                            <div class="flex justify-end items-center gap-2">
                                <a href="{{ route('autofill-suppliers.edit', $autofillSupplier) }}"
                                class="text-gray-300 hover:text-sky-700 max-w-fit">
                                    {{ __('Edit') }}
                                </a>
                                <div class="text-gray-300 hover:text-red-600 cursor-pointer max-w-fit"
                                id="delete-button-{{ $autofillSupplier->id }}">
                                    {{ __('Delete') }}
                                </div>
                                @include('components.delete-modal', [
                                    'model' => $autofillSupplier,
                                    'route' => 'autofill-suppliers',
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
