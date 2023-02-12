@extends('layouts.auth')

@section('title', 'Забыли пароль')

@section('content')

    <div class="form-wrapper">

        <h1 class="mb-4">Забыли пароль</h1>

        <form method="POST" action="{{ route('forgot.handle') }}" autocomplete="off" class="border border-light-subtle rounded p-4">

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

            <button type="submit" class="btn btn-primary">Отправить</button>

            @if (session('status'))
                <div class="alert alert-{{ session('class') }} mt-3">
                    {{ session('status') }}
                </div>
            @endif

            <div class="d-grid gap-3 d-flex mt-4">
                <a href="{{ route('login') }}" class="">Вспомнил пароль</a>
            </div>

        </form>

    </div>

@endsection


