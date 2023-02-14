@extends('layouts.panel')

@section('title', 'Управление счетами')

@section('content')

    <h1 class="mb-5">Выставленные мне</h1>

    @if ($invoices->count() === 0)
        @include('panel.invoice.empty')
    @else
        @include('panel.invoice.list')
    @endif

    <a href="#" class="btn btn-primary mt-2" data-bs-toggle="modal" data-bs-target="#create-invoice">Выставить счет</a>

    @include('panel.invoice.modals.create')
    @include('panel.invoice.modals.paid')

@endsection
