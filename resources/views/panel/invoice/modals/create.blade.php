<div id="create-invoice" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <form action="{{ route('panel-invoices.create') }}" method="POST">

                    @csrf

                    <div class="mb-3">
                        <label for="wallet" class="form-label">Кошелек для зачисления</label>
                        <select name="wallet_id" id="wallet" class="form-control @error('wallet_id') is-invalid @enderror">
                            @foreach($user->wallets as $wallet)
                                <option value="{{ $wallet->id }}">{{ $wallet->number }} ({{ $wallet->currency->code }})</option>
                            @endforeach
                        </select>

                        @error('wallet_id')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Почта пользователя</label>
                        <input type="text" name="email" id="email" class="form-control @error('email') is-invalid @enderror" required>

                        @error('email')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="amount" class="form-label">Сумма</label>
                        <input type="text" name="amount" id="amount" class="form-control @error('amount') is-invalid @enderror" required>

                        @error('amount')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary">Выставить счет</button>

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
