@extends('layouts.dark')

@section('html-class')
bg-gray-900
@endsection

@section('content')
    <div class="flex min-h-full flex-col items-center px-6 mt-40 lg:px-8">
        <div class="">
            <div class="sm:mx-auto sm:w-full sm:max-w-sm">
                <img class="mx-auto w-[300px] h-auto mb-10" src="{{ asset('images/logo.png') }}" alt="Your Company">
            </div>
    
            <div class="sm:mx-auto sm:w-full sm:max-w-sm">
                <form class="space-y-6" action="{{ route('login') }}" method="POST">
                    @csrf
    
                    <div>
                        <div class="mt-2">
                            <input id="email" value="{{ old('email') }}" name="email" type="email"
                            autocomplete="email" required class="field-dark" placeholder="Email address">
                        </div>
                        @if (!empty($errors->get('email')))
                            @include('components.input-error', ['messages' => $errors->get('email')])
                        @endif
                    </div>
    
                    <div>
                        <div class="mt-2">
                            <input id="password" name="password" type="password" autocomplete="current-password"
                            required class="field-dark" placeholder="Password">
                        </div>
                    </div>
    
                    <button type="submit" class="btn-sky">
                        {{ __('Sign in') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
