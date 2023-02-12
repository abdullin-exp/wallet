@extends('layouts.panel')

@section('title', 'Управление счетами')

@section('content')

    <h1 class="mb-5">Управление счетами</h1>

    <div class="d-flex mb-4">
        <div class="btn-group" role="group" aria-label="Default button group">
            <a href="{{ route('panel-invoices', ['exposed' => 'to']) }}"
               class="btn btn-outline-dark {{ request()->is('*/invoices/to') ? 'active' : '' }}">Выставленные мне</a>
            <a href="{{ route('panel-invoices', ['exposed' => 'from']) }}"
               class="btn btn-outline-dark {{ request()->is('*/invoices/from') ? 'active' : '' }}">Выставленные мною</a>
        </div>

        <a href="#" class="btn btn-primary ms-3" data-bs-toggle="modal" data-bs-target="#create-invoice">Выставить счет</a>
    </div>

    @if ($invoices->count() === 0)
        @include('panel.invoice.empty')
    @else
        @include('panel.invoice.list')
    @endif

    @include('panel.invoice.modals.create')
    @include('panel.invoice.modals.paid')

@endsection
