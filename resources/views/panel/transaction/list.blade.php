<table class="table table-bordered align-middle">
    <thead>
    <tr class="text-center">
        <th>Номер транзакции</th>
        <th>Кошелек</th>
        <th>Сумма</th>
        <th>Действие</th>
        <th>Запланировано</th>
        <th>Статус</th>
    </tr>
    </thead>
    <tbody>
        @foreach($transactions as $transaction)
            <tr class="text-center">
                <td>{{ $transaction->id }}</td>
                <td>{{ $transaction->toWallet->number }} {{ $transaction->toWallet->currency->code }}</td>
                <td>{{ $transaction->amount }}</td>
                <td>
                    <span class="badge text-bg-{{ $transaction->type == 'deposit' ? 'success' : 'danger' }}">
                        {{ $transaction->type == 'deposit' ? 'пополнение' : 'списание' }}
                    </span>
                </td>
                <td>
                    @if ($transaction->scheduled_at)
                        <span>{{ $transaction->scheduled_at }}</span>
                        @if ($transaction->type == 'withdraw')
                            <br>
                            <a href="#" class="btn btn-sm btn-primary js-transfer-send-now" data-bs-toggle="modal" data-bs-target="#send-now" data-transfer_id="{{ $transaction->id }}">Отправить сейчас</a>
                        @endif
                    @else
                        нет
                    @endif
                </td>
                <td>{{ $transaction->confirmed ? 'выполнено' : 'не выполнено' }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
