<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>

<body>
    <p>
        {{ $datum->coc }} was created by <b>{{ $datum->user->name }}</b><br>
        Noticed issue: <b>{{ $datum->noticed_issue }}</b><br>
        Currently responsible: <b>{{ $datum->currently_responsible }}</b><br>
        Current status: <b>{{ $datum->status }}</b><br>
        Link: <a href="{{ route('projects.edit', $datum->id) }}">{{ route('projects.edit', $datum->id) }}</a>
    </p>
</body>

</html>
