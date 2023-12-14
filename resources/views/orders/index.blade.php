@extends('layouts.dashboard')

@section('dashboard-content')
    {{-- title --}}
    <div class="flex justify-between mb-3">
        <h2>{{ __('Purchase Orders') }}</h2>
    </div>

    {{-- filters --}}
    <hr>
    <form action="" method="get">
        @csrf
        @method('GET')

        <div class="flex gap-3 my-2 items-center flex-wrap">
        
            {{-- statuses --}}
            @php
                $options = config('models.orders.status');
                array_unshift($options, 'All');
            @endphp
            <label for="status" class="min-w-fit text-white">{{ __('Status') }}</label>
            <select name="status" class="field bg-transparent border-none
            !ring-0 !w-[195px] focus:ring-0 focus:outline-none cursor-pointer">
                @foreach ($options as $optionKey => $optionValue)
                    <option value="{{ $optionKey }}"
                    @if (
                        !empty(request()->query('status')) &&
                        request()->query('status') == $optionKey
                    ) selected @endif>
                        {{ $optionValue }}
                    </option>
                @endforeach
            </select>

            {{-- suppliers --}}
            @php
                $options = \App\Models\Supplier::pluck('name', 'id')->toArray();
                array_unshift($options, 'All');
            @endphp
            <label for="supplier" class="min-w-fit text-white">{{ __('Supplier') }}</label>
            <select name="supplier" class="field bg-transparent border-none
            !ring-0 !w-[195px] focus:ring-0 focus:outline-none cursor-pointer">
                @foreach ($options as $optionKey => $optionValue)
                    <option value="{{ $optionKey }}"
                    @if (
                        !empty(request()->query('supplier')) &&
                        request()->query('supplier') == $optionKey
                    ) selected @endif>
                        {{ $optionValue }}
                    </option>
                @endforeach
            </select>

            {{-- search --}}
            <label for="search" class="min-w-fit text-white">{{ __('Search') }}</label>
            <input type="text" name="search" placeholder="Search..." value="{{ request()->query('search') ?? '' }}"
                class="field-dark max-w-[300px]">

            <input type="hidden" name="page" value="{{ request()->query('page') ?? 1 }}">
            <button type="submit" class="btn-sky max-w-fit">
                {{ __('Filter') }}
            </button>
            <a href="{{ route('orders.index') }}" class="btn-sky-light max-w-fit">
                {{ __('Clear Filters') }}
            </a>
            
        </div>
    </form>
    <hr class="mb-3">

    <ul role="list" class="grid grid-cols-1 gap-6 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
        @foreach ($orders as $order)
            <div class="order-card">
                <div class="order-card-header">
                    <span>{{ $order->po_number }}</span>
                    <x-order.status :status="$order->status" />
                </div>
                <div class="order-card-body">
                    <div class="order-card-body-item">
                        <x-icon.company class="w-5" />
                        <span>{{ $order->supplier->name }}</span>
                    </div>
                    <div class="order-card-body-item">
                        <x-icon.email class="w-5" />
                        <span>{{ $order->supplier->representatives()->first()->email }}</span>
                    </div>
                    <div class="order-card-body-item">
                        <a href=""><x-icon.submission class="w-5 hover:text-sky-700" /></a>
                        <span>{{ $order->submission_code }}</span>
                    </div>
                    <div class="order-card-body-item">
                        <x-icon.spanner class="w-5" />
                        <span>Total parts: 48</span>
                    </div>
                    <div class="order-card-body-item">
                        <x-icon.datetime class="w-5" />
                        <span>{{ $order->created_at }}</span>
                    </div>
                </div>
                <div class="order-card-footer">
                    <a href="#">
                        Part summary <span aria-hidden="true">&rarr;</span>
                    </a>
                </div>
            </div>
        @endforeach
    </ul>

    {{-- pagination --}}
    {{ $orders->appends([
        'search'   => request()->query('search') ?? '',
        'status'   => request()->query('order_by') ?? '0',
        'supplier' => request()->query('order_direction') ?? '0',
    ])->links() }}
@endsection
