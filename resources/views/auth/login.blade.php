@extends('layouts.auth')

@section('title', 'Вход в аккаунт')

@section('content')

    <div class="form-wrapper">

        <h1 class="mb-4">Вход в аккаунт</h1>

        <form method="POST" action="{{ route('login.handle') }}" autocomplete="off" class="border border-light-subtle rounded p-4">

            @csrf

            <div class="mb-3">
                <label for="email" class="form-label">Почта</label>
                <input type="email" name="email" value="{{ old('email') }}" id="email" class="form-control @error('email') is-invalid @enderror" required>
                @error('email')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Пароль</label>
                <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" required>
                @error('password')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary">Войти</button>

            @error('userNotFound')
                <div class="alert alert-warning mt-3">
                    {{ $message }}
                </div>
            @enderror

            @if (session('status'))
                <div class="alert alert-{{ session('class') }} mt-3">
                    {{ session('status') }}
                </div>
            @endif

            <div class="d-grid gap-3 d-flex mt-4">
                <a href="{{ route('forgot') }}" class="">Забыли пароль?</a>
                <a href="{{ route('register') }}" class="">Регистрация</a>
            </div>

        </form>
    </div>
@endsection
