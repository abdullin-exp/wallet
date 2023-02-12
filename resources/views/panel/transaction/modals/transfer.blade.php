<div id="make-transfer" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <form action="{{ route('panel-transactions.makeTransfer') }}" method="POST">

                    @csrf

                    <div class="mb-3">
                        <label for="from_wallet" class="form-label">Кошелек для перевода</label>
                        <select name="from_wallet_id" id="from_wallet" class="form-control @error('from_wallet_id') is-invalid @enderror">
                            @foreach($user->wallets as $wallet)
                                <option value="{{ $wallet->id }}">{{ $wallet->number }} ({{ $wallet->currency->code }})</option>
                            @endforeach
                        </select>

                        @error('from_wallet_id')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="to_wallet" class="form-label">Кошелек получателя</label>
                        <input type="text" name="to_wallet" id="to_wallet" class="form-control @error('to_wallet') is-invalid @enderror" required>

                        @error('to_wallet')
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

                    <div class="mb-3">
                        <label for="scheduled_at" class="form-label">Запланировать</label>
                        <div class="input-group">
                            <input type="text" name="scheduled_at" id="scheduled_at" class="form-control js-transfer-datepicker @error('scheduled_at') is-invalid @enderror">

                            @error('scheduled_at')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">Перевести</button>

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
