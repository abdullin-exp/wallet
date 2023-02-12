@extends('layouts.panel')

@section('title', 'Профиль')

@section('content')

    <h1 class="mb-5">Профиль</h1>

    <form action="{{ route('panel-profile.save') }}" method="POST">

        @csrf

        <input type="hidden" name="user_id" value="{{ $user->id }}">

        <div class="mb-3">
            <label for="last_name" class="form-label">Фамилия</label>
            <input type="text" name="last_name" value="{{ $user->detail->last_name ?? '' }}" id="last_name" class="form-control @error('last_name') is-invalid @enderror">
            @error('last_name')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="first_name" class="form-label">Имя</label>
            <input type="text" name="first_name" value="{{ $user->detail->first_name ?? '' }}" id="first_name" class="form-control @error('first_name') is-invalid @enderror">
            @error('first_name')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="patr_name" class="form-label">Отчество</label>
            <input type="text" name="patr_name" value="{{ $user->detail->patr_name ?? '' }}" id="patr_name" class="form-control @error('patr_name') is-invalid @enderror">
            @error('patr_name')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="birth_date" class="form-label">Дата рождения</label>
            <div class="input-group">
                <input type="text" name="birth_date" value="{{ $user->detail->birth_date ?? '' }}" id="birth_date" class="js-birth-datepicker form-control @error('birth_date') is-invalid @enderror">
                @error('birth_date')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>

        @php
            $gender = $user->detail->gender ?? '';
        @endphp

        <div class="mb-3">
            <label class="form-label">Пол</label>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="gender" value="" id="none" {{ $gender == '' ? 'checked' : '' }}>
                <label class="form-check-label" for="none">Не выбрано</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="gender" value="m" id="men" {{ $gender == 'm' ? 'checked' : '' }}>
                <label class="form-check-label" for="men">Мужской</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="gender" value="w" id="woman" {{ $gender == 'w' ? 'checked' : '' }}>
                <label class="form-check-label" for="woman">Женский</label>
            </div>
        </div>

        <div class="mt-5">
            <button type="submit" class="btn btn-primary">Сохранить</button>
        </div>

        @if (session('status'))
            <div class="alert alert-{{ session('class') }} mt-3">
                {{ session('status') }}
            </div>
        @endif

    </form>

@endsection
