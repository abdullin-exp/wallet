@extends('layouts.auth')

@section('title', 'Восстановление пароля')

@section('content')

    <div class="form-wrapper">

        <h1 class="mb-4">Восстановление пароля</h1>

        <form method="POST" action="{{ route('password-reset.handle') }}" autocomplete="off" class="border border-light-subtle rounded p-4">

            @csrf

            <input type="hidden" name="token" value="{{ $token }}">

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

            <button type="submit" class="btn btn-primary">Обновить пароль</button>

            @if (session('status'))
                <div class="alert alert-{{ session('class') }} mt-3">
                    {{ session('status') }}
                </div>
            @endif

        </form>

    </div>

@endsection
