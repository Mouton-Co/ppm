@extends('layouts.dashboard')

@section('dashboard-content')
    <div class="flex justify-between items-center">
        <h2 class="text-left">{{ __('Suppliers') }}</h2>
        <form action="{{ route('suppliers.index') }}" method="get">
            {{-- search --}}
            <div class="flex items-center justify-start gap-2 smaller-than-711:flex-col smaller-than-711:items-start">
                <input type="text" name="search" placeholder="Search..." value="{{ request()->query('search') ?? '' }}"
                    class="field-dark min-w-[500px]">
            </div>
        </form>
    </div>

    <div class="field-card mt-4 overflow-auto">
        <table class="table-dark">
            <caption class="hidden">{{ __('Supplier index table') }}</caption>
            <thead>
                <tr>
                    <th>{{ __('Name') }}</th>
                    <th>{{ __('Average lead time') }}</th>
                    <th class="flex justify-end w-40">
                        <a href="{{ route('suppliers.create') }}" class="btn btn-sky max-w-fit">
                            {{ __('Add supplier') }}
                        </a>
                    </th>
                </tr>
            </thead>
            <tbody class="hover-row">
                @foreach ($suppliers as $supplier)
                <tr>
                    <td>
                        <a href="{{ route('suppliers.edit', $supplier->id) }}">
                            {{ $supplier->name }}
                        </a>
                    </td>
                    <td>
                        <a href="{{ route('suppliers.edit', $supplier->id) }}">
                            {{ $supplier->average_lead_time ?? 'N/A' }}
                        </a>
                    </td>
                    <td class="w-40">
                        <svg class="h-5 w-5 ml-auto !cursor-pointer" viewBox="0 0 20 20"
                        fill="currentColor" aria-hidden="true" id="delete-button-{{ $supplier->id }}">
                            <path d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0
                            101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75
                            0 00-1.06-1.06L10 8.94 6.28 5.22z"/>
                        </svg>
                        @include('components.delete-modal', [
                            'model'   => $supplier,
                            'route'   => 'suppliers',
                            'method'  => 'DELETE',
                            'message' => "Are you sure you want to delete this supplier? Any parts that have this\n
                            supplier assigned to it, will now have no supplier assigned. Any purchase orders going\n
                            to this supplier will also be removed."
                        ])
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- pagination --}}
    {{ $suppliers->appends([
        'search' => request()->query('search') ?? '',
    ])->links() }}
@endsection
