@extends('layouts.dashboard')

@section('dashboard-content')

    <a href="{{ route('process-types.index') }}" class="btn btn-sky max-w-fit mb-5">
        <x-icon.arrow :direction="'left'" class="w-4 mr-2 aspect-square" />
        {{ __('All') }}
    </a>
    <h2 class="mb-5 text-left">{{ __('Create Process Type') }}</h2>

    @include('components.error-message')

    <div class="field-card">
        <form action="{{ route('process-types.store') }}" method="POST" class="flex flex-col">
            @csrf
        
            @include('process-types.form')

            <div class="flex justify-center w-full gap-3 md:justify-end">
                <input class="btn-sky max-w-none md:max-w-fit" type="submit" value="Create">
            </div>
        </form>
    </div>
    
@endsection
