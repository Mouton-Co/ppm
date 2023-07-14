@extends('layouts.dark')

@section('content')
    <div class="flex min-h-full flex-col justify-center px-6 py-12 lg:px-8">
        <div class="sm:mx-auto sm:w-full sm:max-w-sm">
            <img class="mx-auto h-10 w-auto" src="https://tailwindui.com/img/logos/mark.svg?color=indigo&shade=500"
                alt="Your Company">
            <h2 class="my-5 text-center">
                {{ __('Pro Project Machinary') }}
            </h2>
        </div>

        <div class="sm:mx-auto sm:w-full sm:max-w-sm">
            <form class="space-y-6" action="{{ route('login') }}" method="POST">
                @csrf

                <div>
                    <label for="email" class="label-dark">
                        {{ __('Email address') }}
                    </label>
                    <div class="mt-2">
                        <input id="email" value="{{ old('email') }}" name="email" type="email"
                        autocomplete="email" required class="field-dark">
                    </div>
                    @if (!empty($errors->get('email')))
                        @include('components.input-error', ['messages' => $errors->get('email')])
                    @endif
                </div>

                <div>
                    <div class="flex items-center justify-between">
                        <label for="password" class="label-dark">
                            {{ __('Password') }}
                        </label>
                    </div>
                    <div class="mt-2">
                        <input id="password" name="password" type="password" autocomplete="current-password"
                        required class="field-dark">
                    </div>
                </div>

                <button type="submit" class="btn-indigo">
                    {{ __('Sign in') }}
                </button>
            </form>
        </div>
    </div>
@endsection
