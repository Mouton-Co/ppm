<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <p>Assembly: {{ $part->submission?->assembly_name }}</p>
    <p>Part Name: {{ $part->name }}</p>
    <p>Part ID: {{ $part->id }}</p>
    <p>Submission: {{ $part->submission?->submission_code }}</p><br>

    <p>This part was replaced with the following:</p>
    <p>{{ $part->replaced_by_submission }}</p><br>

    <p><strong>{{ __("PPM ERP System") }}</strong></p>
    <pre>{{ __("Pro Project Machinery (Pty) Ltd.") }}</pre>
</body>
</html>
