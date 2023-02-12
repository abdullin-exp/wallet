<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title')</title>

    <link rel="stylesheet" href="{{ asset('/libs/bootstrap/css/bootstrap.min.css') }}">
</head>
<body>

    <div class="auth-page d-flex min-vh-100 justify-content-center align-items-center">
        @yield('content')
    </div>

    <script src="{{ asset('/libs/bootstrap/js/bootstrap.min.js') }}"></script>
</body>
</html>
