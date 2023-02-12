@extends('layouts.app')

@section('title', 'Онлайн-кошелек')

@section('content')
    <div class="welcome-page d-flex min-vh-100 justify-content-center align-items-center">
        <div class="content">
            <h1 class="mb-4">Онлайн-кошелек</h1>
            <div class="d-grid gap-3 d-flex justify-content-center">
                <a class="btn btn-primary btn-lg px-4 gap-3" href="{{ route('login') }}">Войти</a>
                <a class="btn btn-outline-secondary btn-lg px-4" href="{{ route('register') }}">Регистрация</a>
            </div>
        </div>
    </div>
@endsection
