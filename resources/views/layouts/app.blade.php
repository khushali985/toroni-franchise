<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600&family=Poppins:wght@300;400;500&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    <link rel="stylesheet" href="{{ asset('css/footer.css') }}">
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
    @stack('styles')

    <title>@yield('title', 'Toroni Restaurant')</title>

</head>

<body>
    @php
    $settings = \App\Models\Setting::first();
    @endphp

    @include('components.header')

    @yield('content')

    @include('components.footer')

    <script src="{{ asset('js/header.js') }}"></script>
    @stack('scripts')

</body>

</html>