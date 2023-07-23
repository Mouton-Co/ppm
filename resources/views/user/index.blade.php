@extends('layouts.dashboard')

@section('dashboard-content')
    <h2 class="text-left">Users</h2>

    <div class="field-card mt-4 overflow-auto">
        <table class="table-dark">
            <caption></caption>
            <thead>
                <tr>
                    <th>{{ __('Name') }}</th>
                    <th>{{ __('Email') }}</th>
                    <th>{{ __('Role') }}</th>
                </tr>
            </thead>
            <tbody>
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
                    <td>
                        <a href="{{ route('user.destroy', $user->id) }}">
                            <svg data-dz-remove class="h-5 w-5 !cursor-pointer" viewBox="0 0 20 20"
                            fill="currentColor" aria-hidden="true">
                                <path d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0
                                101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75
                                0 00-1.06-1.06L10 8.94 6.28 5.22z"/>
                            </svg>
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
