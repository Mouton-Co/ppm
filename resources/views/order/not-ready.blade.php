@extends('layouts.dark')

@section('html-class')
    bg-gray-900
@endsection

@section('body-class')
    pt-20
@endsection

@section('content')
    <div class="flex flex-col items-center px-6 lg:px-8">
        <div class="">
            <div class="sm:mx-auto sm:w-full sm:max-w-md">
                <img
                    class="mx-auto mb-10 h-auto w-36"
                    src="{{ asset('images/logo.png') }}"
                    alt="Your Company"
                >
            </div>

            <div class="sm:mx-auto sm:w-full sm:max-w-md">
                <p class="mt-16 text-center text-lg text-gray-500">
                    {{ __('Please select a new date as to when we can expect the order to be ready as well as specifiying a reason as to why the order was delayed. Thank you!') }}
                </p>

                <form
                    class="mt-8"
                    action="{{ route('order.not-ready.submit', [
                        'id' => $order->id,
                        'token' => hash('sha256', $order->id),
                    ]) }}"
                    method="post"
                >
                    @csrf

                    <div class="mt-4 flex flex-col">
                        <label
                            class="text-gray-400"
                            for="due_date"
                        >
                            {{ __('New due date') }}
                        </label>
                        <input
                            class="field-dark mb-5 mt-2"
                            id="due_date"
                            name="due_date"
                            type="date"
                            required
                        >
                    </div>

                    <div class="flex flex-col">
                        <label
                            class="text-gray-400"
                            for="reason"
                        >
                            {{ __('Reason for delay') }}
                        </label>
                        <textarea
                            class="field-dark mb-5 mt-2"
                            id="reason"
                            name="reason"
                            placeholder="{{ __('Please specify the reason for the delay...') }}"
                            cols="30"
                            rows="6"
                            required
                        ></textarea>
                    </div>

                    <div class="mt-8 flex justify-end">
                        <button
                            class="btn btn-sky mb-5 max-w-fit"
                            type="submit"
                        >
                            {{ __('Submit') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
