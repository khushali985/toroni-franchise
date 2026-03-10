<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600&family=Poppins:wght@300;400;500&display=swap"
        rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/navbar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/footer.css') }}">

    @stack('styles')

    <title>@yield('title', 'Admin Panel')</title>

</head>

<body>
    <div class="admin-wrapper">

        {{-- navbar --}}
        @include('components.navbar')

        {{-- Main Content --}}
        <div class="main-content">

            <div class="content-area container-fluid">
                @yield('content')
            </div>


            {{-- Footer --}}
            @include('components.footer')

        </div>


    </div>

    @stack('scripts')

    <script src="{{ asset('js/navbar.js') }}"></script>
</body>

</html>