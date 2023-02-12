@extends('layouts.panel')

@section('title', 'Кошельки')

@section('content')

    <h1 class="mb-5">Кошельки</h1>

    @if ($wallets->count() === 0)
        @include('panel.wallet.empty')
    @else
        @include('panel.wallet.list')
    @endif

    <a href="#" class="btn btn-primary mt-2" data-bs-toggle="modal" data-bs-target="#create-wallet">Создать кошелек</a>

    @include('panel.wallet.modals.create')
    @include('panel.wallet.modals.deposit')

@endsection
