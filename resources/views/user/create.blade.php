@extends('layouts.dashboard')

@section('dashboard-content')
    <a href="{{ route('user.index') }}" class="btn btn-sky max-w-fit mb-5">
        <svg class="w-4 mr-2 aspect-square" viewBox="0 0 1024 1024" xmlns="http://www.w3.org/2000/svg"
        fill="currentColor">
            <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
            <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
            <g id="SVGRepo_iconCarrier">
                <path fill="currentColor" d="M224 480h640a32 32 0 1 1 0 64H224a32 32 0 0 1 0-64z"></path>
                <path fill="currentColor" d="m237.248 512 265.408 265.344a32 32 0 0 1-45.312 45.312l-288-288a32 32
                0 0 1 0-45.312l288-288a32 32 0 1 1 45.312 45.312L237.248 512z"></path>
            </g>
        </svg>
        {{ __('All users') }}
    </a>
    <h2 class="mb-5 text-left">{{ __('Create user') }}</h2>

    @include('components.error-message')

    <div class="field-card">
        <form action="{{ route('user.store') }}" method="POST" class="flex flex-col">
            @csrf
        
            @include('user.form')

            <div class="flex justify-center w-full gap-3 md:justify-end">
                <input class="btn-sky max-w-none md:max-w-fit" type="submit" value="Create">
            </div>
        </form>
    </div>
    
@endsection
