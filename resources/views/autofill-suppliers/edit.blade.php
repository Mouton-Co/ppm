@extends('layouts.dashboard')

@section('dashboard-content')

    <a href="{{ route('autofill-suppliers.index') }}" class="btn btn-sky max-w-fit mb-5">
        <x-icon.arrow :direction="'left'" class="w-4 mr-2 aspect-square" />
        {{ __('All') }}
    </a>
    <h2 class="mb-5 text-left">{{ __('Edit Autofill Supplier') }}</h2>

    @include('components.error-message')

    <div class="field-card">
        <form action="{{ route('autofill-suppliers.update', $autofillSupplier) }}" method="POST" class="flex flex-col">
            @csrf
            @method('PUT')
        
            @include('autofill-suppliers.form', ['edit' => true])

            <div class="flex justify-center w-full gap-3 md:justify-end">
                <input class="btn-sky max-w-none md:max-w-fit" type="submit" value="Update">
            </div>
        </form>
    </div>
    
@endsection
