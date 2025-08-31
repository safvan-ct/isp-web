<!DOCTYPE html>
<html lang="ml">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title class="notranslate">@yield('title', __('app.islamic_study_portal'))</title>

    <!-- Favicons -->
    <link rel="icon" sizes="32x32" href="{{ asset('img/favicon-32x32.png') }}">
    <link rel="icon" sizes="16x16" href="{{ asset('img/favicon-16x16.png') }}">
    <link rel="shortcut icon" href="{{ asset('img/favicon.ico') }}">
    <link rel="icon" sizes="192x192" href="{{ asset('img/android-chrome-192x192.png') }}">
    <link rel="icon" sizes="512x512" href="{{ asset('img/android-chrome-512x512.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('img/apple-touch-icon.png') }}">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('web/css/custom.css') }}">
    @stack('styles')
</head>

<body>
    <div class="loader" id="pageLoader">
        <div class="spinner"></div>
    </div>

    @yield('content')

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('web/js/custom.js') }}"></script>

    <script>
        // Page loader
        $(function() {
            setTimeout(() => $('#pageLoader').fadeOut('slow'), 500);
        });

        // Adjust wrapper height for mobile keyboards
        const wrapper = document.getElementById('loginWrapper');

        function adjustHeight() {
            wrapper.style.height = window.innerHeight + 'px';
        }
        adjustHeight();
        window.addEventListener('resize', adjustHeight);

        // Scroll inputs into view when focused
        document.querySelectorAll('input').forEach(input => {
            input.addEventListener('focus', () => setTimeout(() => input.scrollIntoView({
                behavior: 'smooth',
                block: 'center'
            }), 300));
        });
    </script>
</body>

</html>
