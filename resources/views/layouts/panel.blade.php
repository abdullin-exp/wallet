<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title')</title>

    <link rel="stylesheet" href="{{ asset('/libs/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/libs/datepicker/css/datepicker.minimal.css') }}">

    <link rel="stylesheet" href="{{ asset('/css/panel.css') }}">
</head>
<body>

    <header class="p-3 mb-3 border-bottom">
        <div class="container">
            <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
                <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
                    <li><a href="{{ route('panel-wallets') }}" class="nav-link px-2 link-dark">Кошельки</a></li>
                    <li><a href="{{ route('panel-transactions') }}" class="nav-link px-2 link-dark">Транзакции</a></li>
                    <li><a href="{{ route('panel-invoices', ['exposed' => 'to']) }}" class="nav-link px-2 link-dark">Счета</a></li>
                </ul>

                <div class="dropdown text-end">
                    <a href="#" class="d-block link-dark text-decoration-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                        {{ auth()->user()->name }}
                    </a>
                    <ul class="dropdown-menu text-small">
                        <li><a class="dropdown-item" href="{{ route('panel-profile') }}">Профиль</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form action="{{ route('logOut') }}" method="POST">

                                @csrf

                                @method('DELETE')

                                <button type="submit" class="link-dark btn">
                                    Выйти
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </header>

    <main class="py-5">
        <div class="container">
            @yield('content')
        </div>
    </main>

    <script src="{{ asset('/libs/jquery/jquery-3.6.3.min.js') }}"></script>
    <script src="{{ asset('/libs/datepicker/datepicker.js') }}"></script>
    <script src="{{ asset('/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    <script src="{{ asset('/js/panel.js') }}"></script>
</body>
</html>
