<table class="table table-bordered align-middle">
    <thead>
    <tr class="text-center">
        <th>Отправитель</th>
        <th>Получатель</th>
        <th>Сумма</th>
        <th>Запланировано</th>
    </tr>
    </thead>
    <tbody>
        @foreach($transfers as $transfer)
            <tr class="text-center">
                <td>{{ $transfer->walletFrom->number }} ({{ $transfer->walletFrom->currency->code }})</td>
                <td>{{ $transfer->walletTo->number }} ({{ $transfer->walletTo->currency->code }})</td>
                <td>{{ $transfer->amount }}</td>
                <td>
                    @if ($transfer->scheduled_date)
                        <span class="d-flex w-100 justify-content-center">{{ $transfer->scheduled_date }}</span>
                        @if ($user->id != $transfer->to_user_id)
                            <span class="d-flex mt-1 w-100 justify-content-center">
                                <a href="#" class="btn btn-sm btn-primary js-transfer-send-now" data-bs-toggle="modal" data-bs-target="#send-now" data-transfer_id="{{ $transfer->id }}">Отправить</a>
                                <a href="#" class="btn btn-sm btn-danger js-transfer-cancel-now ms-1" data-bs-toggle="modal" data-bs-target="#cancel-now" data-transfer_id="{{ $transfer->id }}">Отменить</a>
                            </span>
                        @endif
                    @else
                        нет
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
