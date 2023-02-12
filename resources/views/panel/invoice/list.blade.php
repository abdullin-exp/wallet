<table class="table table-bordered align-middle">
    <thead>
    <tr>
        <th>Номер счета</th>
        <th class="text-center">Сумма</th>
        <th class="text-center">Статус</th>
        <th class="text-center">Действия</th>
    </tr>
    </thead>
    <tbody>
        @foreach($invoices as $invoice)
            <tr>
                <td>{{ $invoice->id }}</td>
                <td class="text-center">{{ $invoice->amount  }}</td>
                <td class="text-center">
                    {{ $invoice->status == 'paid' ? 'оплачено' : 'ждет оплаты' }}
                </td>
                <td class="text-center">
                    @if ($invoice->to_user_id == $user->id && $invoice->status == 'processing')
                        <a href="#" class="btn btn-primary js-invoice-paid" data-bs-toggle="modal" data-bs-target="#paid-invoice" data-invoice_id="{{ $invoice->id }}">Оплатить</a>
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
