@extends('layouts.dark')

@section('html-class')
    bg-gray-900
@endsection

@section('body-class')
    pt-40
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
                <h1 class="text-7xl text-gray-400 text-center">{{ $title ?? '' }}</h1>
                <p class="text-lg text-gray-500 text-center mt-16">
                    {{ $message ?? '' }}
                </p>
            </div>
        </div>
    </div>
@endsection
