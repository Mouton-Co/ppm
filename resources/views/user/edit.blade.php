@extends('layouts.dashboard')

@section('dashboard-content')
    @if ($user->id == auth()->user()->id)
        <h2 class="mb-5 text-left">{{ __('Edit your details') }}</h2>
    @else
        <h2 class="mb-5 text-left">{{ __('Edit user') }}</h2>
    @endif

    @include('components.error-message')

    <div class="field-card">
        <form action="{{ route('user.update', $user->id) }}" method="POST" class="flex flex-col">
            @csrf
        
            @include('user.form')

            <div class="flex justify-center w-full gap-3 md:justify-end">
                <input class="btn-sky max-w-none md:max-w-fit" type="submit" value="Update">
                @if ($user->id != auth()->user()->id)
                    <div id="delete-button-{{ $user->id }}"
                    class="btn-sky-light w-full max-w-none md:max-w-fit">{{__('Delete')}}</div>
                @endif
            </div>
        </form>
    </div>
    
    @include('components.delete-modal', ['user' => $user])
@endsection
