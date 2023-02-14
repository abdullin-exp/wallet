<table class="table table-bordered align-middle">
    <thead>
    <tr class="text-center">
        <th>Номер транзакции</th>
        <th>Кошелек</th>
        <th>Сумма</th>
        <th>Действие</th>
    </tr>
    </thead>
    <tbody>
        @foreach($transactions as $transaction)
            <tr class="text-center">
                <td>{{ $transaction->id }}</td>
                <td>{{ $transaction->wallet->number }} {{ $transaction->wallet->currency->code }}</td>
                <td>{{ $transaction->amount }}</td>
                <td>
                    <span class="badge text-bg-{{ $transaction->type == 'deposit' ? 'success' : 'danger' }}">
                        {{ $transaction->type == 'deposit' ? 'пополнение' : 'списание' }}
                    </span>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
