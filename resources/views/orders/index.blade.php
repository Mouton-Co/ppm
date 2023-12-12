@extends('layouts.dashboard')

@section('dashboard-content')
    {{-- title --}}
    <div class="flex justify-between mb-3">
        <h2>{{ __('Purchase Orders') }}</h2>
    </div>

    <ul role="list" class="grid grid-cols-1 gap-6 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
        @for ($i = 0; $i < 8; $i++)
            <div class="order-card">
                <div class="order-card-header">
                    <span>PO: 12-12-0001</span>
                    <x-order.status :status="'pending'" />
                </div>
                <div class="order-card-body">
                    <div class="order-card-body-item">
                        <x-icon.company class="w-5" />
                        <span>Auto works</span>
                    </div>
                    <div class="order-card-body-item">
                        <x-icon.email class="w-5" />
                        <span>autoworks@gmail.com</span>
                    </div>
                    <div class="order-card-body-item">
                        <a href=""><x-icon.submission class="w-5 hover:text-sky-700" /></a>
                        <span>ADR202312121604231</span>
                    </div>
                    <div class="order-card-body-item">
                        <x-icon.spanner class="w-5" />
                        <span>Total parts: 48</span>
                    </div>
                </div>
                <div class="order-card-footer">
                    <a href="#">
                        Part summary <span aria-hidden="true">&rarr;</span>
                    </a>
                </div>
            </div>
        @endfor
    </ul>
@endsection
