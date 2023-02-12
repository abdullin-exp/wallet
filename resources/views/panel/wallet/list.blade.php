<table class="table table-bordered align-middle">
    <thead>
        <tr>
            <th>Номер кошелька</th>
            <th class="text-center">Баланс</th>
            <th class="text-center">Валюта</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        @foreach($wallets as $wallet)
            <tr>
                <td>{{ $wallet->number }}</td>
                <td class="text-center">{{ $wallet->balance }}</td>
                <td class="text-center">{{ $wallet->currency->code }}</td>
                <td class="text-center">
                    <a href="#" class="btn btn-success js-wallet-deposit" data-bs-toggle="modal" data-bs-target="#deposit" data-wallet_id="{{ $wallet->id }}">пополнить</a>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
