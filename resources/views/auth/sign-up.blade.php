@extends('layouts.auth')

@section('title', 'Регистрация')

@section('content')

    <div class="form-wrapper">

        <h1 class="mb-4">Регистрация</h1>

        <form method="POST" action="{{ route('register.handle') }}" autocomplete="off" class="border border-light-subtle rounded p-4">

            @csrf

            <div class="mb-3">
                <label for="name" class="form-label">Логин</label>
                <input type="text" name="name" value="{{ old('name') }}" id="name" class="form-control @error('name') is-invalid @enderror" required>
                @error('name')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

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

            <div class="mb-3">
                <label for="password_confirmation" class="form-label">Повторите пароль</label>
                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control @error('password_confirmation') is-invalid @enderror" required>
                @error('password_confirmation')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary">Зарегистрироваться</button>

            <div class="d-grid gap-3 d-flex mt-4">
                <a href="{{ route('login') }}" class="">Войти</a>
            </div>

        </form>

    </div>

@endsection

