@extends('layouts.panel')

@section('title', 'Транзакции')

@section('content')

    <h1 class="mb-5">Транзакции</h1>

    @if ($transactions->count() === 0)
        @include('panel.transaction.empty')
    @else
        @include('panel.transaction.list')
    @endif

@endsection
