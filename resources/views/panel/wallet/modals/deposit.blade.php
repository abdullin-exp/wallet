<div id="deposit" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <form action="{{ route('panel-wallets.deposit') }}" method="POST">

                    @csrf

                    <input type="hidden" name="wallet_id" value="">

                    <div class="mb-3">
                        <label for="amount" class="form-label">Сумма</label>
                        <input type="text" name="amount" id="amount" class="form-control @error('amount') is-invalid @enderror" required>

                        @error('amount')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary">Пополнить</button>

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
