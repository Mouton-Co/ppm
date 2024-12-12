@extends('layouts.dashboard')

@section('dashboard-content')
    <a
        class="btn btn-sky mb-5 max-w-fit"
        href="{{ route('roles.index') }}"
    >
        <svg
            class="mr-2 aspect-square w-4"
            viewBox="0 0 1024 1024"
            xmlns="http://www.w3.org/2000/svg"
            fill="currentColor"
        >
            <g
                id="SVGRepo_bgCarrier"
                stroke-width="0"
            ></g>
            <g
                id="SVGRepo_tracerCarrier"
                stroke-linecap="round"
                stroke-linejoin="round"
            ></g>
            <g id="SVGRepo_iconCarrier">
                <path
                    fill="currentColor"
                    d="M224 480h640a32 32 0 1 1 0 64H224a32 32 0 0 1 0-64z"
                ></path>
                <path
                    fill="currentColor"
                    d="m237.248 512 265.408 265.344a32 32 0 0 1-45.312 45.312l-288-288a32 32
                        0 0 1 0-45.312l288-288a32 32 0 1 1 45.312 45.312L237.248 512z"
                ></path>
            </g>
        </svg>
        {{ __('All roles') }}
    </a>
    <h2 class="mb-5 text-left">{{ __('Edit role') }}</h2>

    @include('components.error-message')

    <form
        class="flex flex-col"
        action="{{ route('roles.update', $role->id) }}"
        method="POST"
    >
        @method('PUT')
        @csrf

        <div class="mb-5 flex items-center gap-3">
            <input
                class="mb-1"
                id="customer-role"
                name="customer"
                type="checkbox"
                {{ $role->customer ? 'checked' : '' }}
            >
            <label
                class="label-dark"
                for="customer-role"
            >{{ __('Customer role') }}</label>
        </div>

        <div
            class="{{ !empty($role) && !$role->customer ? '' : 'hidden' }}"
            id="standard-form"
        >
            @include('roles.standard-form')
        </div>
        <div id="customer-form"class="{{ !empty($role) && $role->customer ? '' : 'hidden' }}">
            @include('roles.customer-form')
        </div>

        <div class="flex w-full justify-center gap-3 md:justify-end">
            <input
                class="btn-sky max-w-none md:max-w-fit"
                type="submit"
                value="Update"
            >
        </div>
    </form>
@endsection
