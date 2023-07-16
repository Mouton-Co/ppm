@extends('layouts.dashboard')

@section('dashboard-content')
    <h2 class="text-left">Submissions</h2>

    <div class="field-card mt-4 overflow-scroll">
        <table class="table-dark">
            <caption></caption>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Code</th>
                    <th>Machine number</th>
                    <th>Type of submission</th>
                    <th>Unit number</th>
                    <th>Designer</th>
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
