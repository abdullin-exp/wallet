@extends('layouts.panel')

@section('title', 'Переводы')

@section('content')

    <h1 class="mb-5">Переводы</h1>

    @if ($transfers->count() === 0)
        @include('panel.transfer.empty')
    @else
        @include('panel.transfer.list')
    @endif

    <a href="#" class="btn btn-primary mt-2" data-bs-toggle="modal" data-bs-target="#make-transfer">Сделать перевод</a>

    @include('panel.transfer.modals.make')
    @include('panel.transfer.modals.send-now')
    @include('panel.transfer.modals.cancel-now')

@endsection
