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
        <a href="{{ route('submissions.show', $replacement->id) }}">
            {{ $replacement->submission_code }}
        </a>
        replaced
        <a href="{{ route('submissions.show', $original->id) }}">
            {{ $original->submission_code }}
        </a>
    </p>

    <p>
        The following parts have been replaced:
    </p>

    <ul>
        @foreach ($replacements as $id => $value)
            @if ($value == "on")
                @php
                    $originalPart = App\Models\Part::find($replacementOptions[$id]['original']);
                    $replacementPart = App\Models\Part::find($replacementOptions[$id]['new']);
                @endphp
                <li>
                    @if ($replacementOptions[$id]['reason'] == "QTY changed")
                        {{ __("({$replacementPart->quantity})") }}
                    @endif
                    {{ $replacementPart->name }}
                    {{ __(" => ") }}
                    @if ($replacementOptions[$id]['reason'] == "QTY changed")
                        {{ __("({$originalPart->quantity})") }}
                    @endif
                    {{ $originalPart->name }}
                </li>
            @endif
        @endforeach
    </ul>

    <p><strong>{{ __("PPM ERP System") }}</strong></p>
    <pre>{{ __("Pro Project Machinery (Pty) Ltd.") }}</pre>
</body>
</html>
