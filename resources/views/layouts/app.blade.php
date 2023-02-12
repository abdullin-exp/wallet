<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title')</title>

    <link rel="stylesheet" href="{{ asset('/css/main.css') }}">
    <link rel="stylesheet" href="{{ asset('/libs/bootstrap/css/bootstrap.min.css') }}">
</head>
<body>

    @yield('content')

    <script src="{{ asset('/libs/bootstrap/js/bootstrap.min.js') }}"></script>
</body>
</html>
