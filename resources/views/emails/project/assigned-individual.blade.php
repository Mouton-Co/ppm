<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>

<body>
    <p>Good day {{ $datum->currently_responsible ?? 'N/A' }},</p>

    <p>A CoC Item has been assigned to you.</p>
    <p>
        CoC Nr: {{ $datum->coc ?? 'N/A' }}<br>
        Link to CoC item: <a href="{{ route('projects.edit', $datum->id) }}">
            {{ route('projects.edit', $datum->id) }}
        </a><br>
    </p>
</body>

</html>
