<div id="create-wallet" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <form action="{{ route('panel-wallets.create') }}" method="POST">

                    @csrf

                    <div class="mb-3">
                        <label for="currency" class="form-label">Выберите валюту</label>
                        <select name="currency_id" id="currency" class="form-control @error('currency_id') is-invalid @enderror">
                            @foreach($currencies as $currency)
                                <option value="{{ $currency->id }}">{{ $currency->name }} ({{ $currency->code }})</option>
                            @endforeach
                        </select>

                        @error('currency_id')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary">Создать</button>

                    @if (session('status'))
                        <div class="alert alert-{{ session('class') }} mt-3">
                            {{ session('status') }}
                        </div>
                    @endif

                </form>
            </div>
        </div>
    </div>
</div>
