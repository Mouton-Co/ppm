@extends('layouts.dashboard')

@section('dashboard-content')
    <div class="flex justify-between items-center">
        <h2 class="text-left">{{ __('Users') }}</h2>
        <a href="{{ route('user.create') }}" class="btn btn-sky max-w-fit">{{ __('Add user') }}</a>
    </div>

    <div class="field-card mt-4 overflow-auto">
        <table class="table-dark">
            <caption class="hidden">{{ __('User index table') }}</caption>
            <thead>
                <tr>
                    <th>{{ __('Name') }}</th>
                    <th>{{ __('Email') }}</th>
                    <th>{{ __('Role') }}</th>
                </tr>
            </thead>
            <tbody class="hover-row">
                @foreach ($users as $user)
                <tr>
                    <td>
                        <a href="{{ route('user.edit', $user->id) }}">
                            {{ $user->name }}
                        </a>
                    </td>
                    <td>
                        <a href="{{ route('user.edit', $user->id) }}">
                            {{ $user->email }}
                        </a>
                    </td>
                    <td>
                        <a href="{{ route('user.edit', $user->id) }}">
                            {{ $user->role->role }}
                        </a>
                    </td>
                    @if ($user->id != auth()->user()->id)
                        <td>
                            <svg class="h-5 w-5 !cursor-pointer" viewBox="0 0 20 20"
                            fill="currentColor" aria-hidden="true" id="delete-button-{{ $user->id }}">
                                <path d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0
                                101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75
                                0 00-1.06-1.06L10 8.94 6.28 5.22z"/>
                            </svg>
                            @include('components.delete-modal', ['user' => $user])
                        </td>
                    @endif
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
