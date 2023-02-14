<div id="paid-invoice" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <form action="{{ route('panel-invoices.pay') }}" method="POST">

                    @csrf

                    <input type="hidden" name="invoice_id" value="">

                    <div class="mb-3">
                        <label for="wallet" class="form-label">Кошелек для оплаты</label>
                        <select name="wallet_id" id="wallet" class="js-select-wallet-for-show-balance form-control @error('wallet_id') is-invalid @enderror">
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


                    <div class="list-available-amount mb-3">
                        @foreach($user->wallets as $key => $wallet)
                            <div class="alert alert-info {{ $key !== 0 ? 'visually-hidden' : '' }}" data-wallet_id="{{ $wallet->id }}">
                                Доступно: <span class="value"><strong>{{ $wallet->balance . ' ' . $wallet->currency->code }}</strong></span>
                            </div>
                        @endforeach
                    </div>


                    <button type="submit" class="btn btn-primary">Оплатить</button>

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
