@extends('layouts.dashboard')

@section('dashboard-content')
    {{-- title --}}
    <div class="flex mb-3 items-center gap-3">
        <h2>{{ __('Purchase Orders') }}</h2>

        <a href="{{ route('orders.generate') }}" class="btn-sky max-w-fit">
            {{ __('Generate orders') }}
        </a>
    </div>

    {{-- curtain --}}
    <div id="modal-curtain" class="hidden"></div>

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

    {{-- list of orders --}}
    <ul role="list" class="grid grid-cols-1 gap-6 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
        @foreach ($orders as $order)
            {{-- order card --}}
            <div id="order-card-{{ $order->id }}" class="order-card-{{$order->status}} order-card-hover">
                <div class="order-card-header">
                    <span>PO {{ $order->po_number ?? 'N/A' }}</span>
                    <x-order.status :status="$order->status ?? 'N/A'" />
                </div>
                <div class="order-card-body">
                    <div class="order-card-body-item">
                        <x-icon.company class="w-5" />
                        <span>{{ $order->supplier->name  ?? 'N/A'}}</span>
                    </div>
                    <div class="order-card-body-item">
                        <x-icon.email class="w-5" />
                        <span>{{ $order->supplier->representatives()->first()->email ?? 'N/A' }}</span>
                    </div>
                    <div class="order-card-body-item">
                        <x-icon.submission class="w-5" />
                        <span>{{ $order->submission->submission_code ?? '## submission deleted' }}</span>
                    </div>
                    <div class="order-card-body-item">
                        <x-icon.spanner class="w-5" />
                        <span>Total parts: {{ $order->total_parts ?? 'N/A' }}</span>
                    </div>
                    <div class="order-card-body-item">
                        <x-icon.datetime class="w-5" />
                        <span>{{ $order->created_at ?? 'N/A' }}</span>
                    </div>
                </div>
                <div class="order-card-footer">
                    <div>
                        {{ __('Purchase order summary') }} <span aria-hidden="true">&rarr;</span>
                    </div>
                    @if ($order->status == 'ordered')
                        <x-icon.checkmark class="w-5 text-green-500" />
                    @endif
                </div>
            </div>

            {{-- order modal --}}
            <div id="order-modal-{{ $order->id }}" class="hidden purchase-order-modal smaller-than-572:px-4">
                <div class="order-card-{{$order->status}} min-w-[34rem] smaller-than-572:min-w-full
                max-h-[700px] overflow-y-scroll">
                    <div class="order-card-header">
                        <span>PO {{ $order->po_number ?? 'N/A' }}</span>
                        <x-order.status :status="$order->status ?? 'N/A'" />
                    </div>
                    <div class="order-card-body">
                        @foreach ($order->parts()->get() as $part)
                            <div class="order-card-body-item w-full justify-between">
                                <span>{{ $part->name ?? 'N/A' }}</span>
                                <span>{{ $part->quantity ?? 'N/A' }}</span>
                            </div>
                        @endforeach
                    </div>
                    @if ($order->status == 'ordered')
                        <div class="order-card-footer">
                            <div class="flex gap-3">
                                <span class="text-green-500">{{ __('Parts ordered') }}</span>
                                <x-icon.checkmark class="w-5 text-green-500" />
                            </div>
                        </div>
                    @else
                        <div class="order-card-footer flex justify-between items-center">
                            <a href="{{ route('email.purchase-order.render', $order->id) }}" class="hover:text-sky-600">
                                <span>{{ __('Prepare email') }}</span>
                                <span aria-hidden="true">&rarr;</span>
                            </a>
                            <a href="{{ route('orders.complete', $order->id) }}" class="hover:text-sky-600 text-right">
                                <span>{{ __('Mark as ordered') }}</span>
                                <span aria-hidden="true">&rarr;</span>
                            </a>
                        </div>
                    @endif
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
