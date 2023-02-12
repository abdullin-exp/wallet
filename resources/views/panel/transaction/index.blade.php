@extends('layouts.panel')

@section('title', 'Транзакции')

@section('content')

    <h1 class="mb-5">Транзакции</h1>

    @if ($transactions->count() === 0)
        @include('panel.transaction.empty')
    @else
        @include('panel.transaction.list')
    @endif

    <a href="#" class="btn btn-primary mt-2" data-bs-toggle="modal" data-bs-target="#make-transfer">Сделать перевод</a>

    @include('panel.transaction.modals.transfer')
    @include('panel.transaction.modals.send-now')

@endsection
