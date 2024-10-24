<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>

<body>
    <p>Good day,</p>

    <p>A new submission has been created.</p>
    <p>
        Submission Name: {{ $datum->assembly_name ?? 'N/A' }}<br>
        Submission Code: {{ $datum->submission_code ?? 'N/A' }}<br>
        Submission Type: {{ \App\Models\Submission::$structure['submission_type']['casts'][$datum->submission_type] ?? 'N/A' }}<br>
        Link to Submission: <a href="{{ route('submissions.show', $datum->id) }}">
            {{ route('submissions.show', $datum->id) }}
        </a><br>
    </p>

    <p><strong>{{ __("PPM ERP System") }}</strong></p>
    <pre>{{ __("Pro Project Machinery (Pty) Ltd.") }}</pre>
</body>

</html>
