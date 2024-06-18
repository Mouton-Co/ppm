<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full @yield('html-class')">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ env('APP_NAME') }}</title>
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/png">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://rsms.me/inter/inter.css">

    <!-- Scripts -->
    @vite(['resources/scss/main.scss', 'resources/js/app.js'])
    @yield('custom-scripts')
</head>
<body class="h-full font-sans antialiased @yield('body-class')">
    <div id="curtain" class="opacity-0 inset-0 bg-gray-900/80 z-40 curtain-closed"></div>

    @yield('content')

    @yield('end-body-scripts')

    {{-- used for column sorting --}}
    <script src="https://unpkg.com/sortablejs-make/Sortable.min.js"></script>
    <script src="https://code.jquery.com/jquery-2.2.4.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-sortablejs@latest/jquery-sortable.js"></script>

</body>
</html>
