@extends('layouts.dashboard')

@section('dashboard-content')
    <div class="flex justify-between items-center">
        <h2 class="text-left">{{ __('Submissions') }}</h2>
        <a href="{{ route('new.submission') }}" class="btn btn-sky max-w-fit">{{ __('Add submission') }}</a>
    </div>

    <div class="field-card mt-4 overflow-auto">
        <table class="table-dark">
            <caption></caption>
            <thead>
                <tr>
                    <th>{{ __('Name') }}</th>
                    <th>{{ __('Code') }}</th>
                    <th>{{ __('Machine number') }}</th>
                    <th>{{ __('Type of submission') }}</th>
                    <th>{{ __('Unit number') }}</th>
                    <th>{{ __('Designer') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($submissions as $submission)
                <tr>
                    <td>
                        <a href="{{ route('submissions.view', $submission->id) }}">
                            {{ $submission->assembly_name }}
                        </a>
                    </td>
                    <td>
                        <a href="{{ route('submissions.view', $submission->id) }}">
                            {{ $submission->submission_code }}
                        </a>
                    </td>
                    <td>
                        <a href="{{ route('submissions.view', $submission->id) }}">
                            {{ $submission->machine_number }}
                        </a>
                    </td>
                    <td>
                        <a href="{{ route('submissions.view', $submission->id) }}">
                            {{ $submission->formatted_submission_type }}
                        </a>
                    </td>
                    <td>
                        <a href="{{ route('submissions.view', $submission->id) }}">
                            {{ $submission->formatted_unit_number }}
                        </a>
                    </td>
                    <td>
                        <a href="{{ route('submissions.view', $submission->id) }}">
                            {{ $submission->user->name }}
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
