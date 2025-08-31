<!doctype html>
<html lang="en">

<head>
    <title>{{ config('app.name') }}</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="icon" href="{{ asset('img/favicon.ico') }}" type="image/x-icon" />
    <link rel="stylesheet" href="{{ asset('admin/css/style.css') }}" id="main-style-link" />
    <link rel="stylesheet" href="{{ asset('admin/css/style-preset.css') }}" />
</head>

<body>
    <div class="loader-bg">
        <div class="loader-track">
            <div class="loader-fill"></div>
        </div>
    </div>

    <div class="auth-main">
        <div class="auth-wrapper v3">
            <div class="auth-form">
                <div class="card mt-5">
                    <div class="card-body">
                        <a href="#" class="d-flex justify-content-center mt-1">
                            <img src="{{ asset('img/logo.png') }}" alt="image" class="img-fluid brand-logo"
                                style="height: 95px" />
                        </a>

                        @yield('content')
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Required Js -->
    <script src="{{ asset('admin/js/script.js') }}"></script>
    <script src="{{ asset('admin/js/plugins/feather.min.js') }}"></script>
</body>

</html>
