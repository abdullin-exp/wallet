$(document).ready(function () {
    let birthDatepicker = new Datepicker('.js-birth-datepicker', {
        max: (function () {
            var date = new Date();
            date.setDate(date.getDate());
            return date;
        })()
    });

    let transferDatepicker = new Datepicker('.js-transfer-datepicker', {
        min: (function () {
            var date = new Date();
            date.setDate(date.getDate());
            return date;
        })()
    });

    $('.js-wallet-deposit').on('click', function (e) {
        e.preventDefault();

        let walletId = $(this).attr('data-wallet_id');

        $('body').find('#deposit').find('input[name=wallet_id]').val(walletId);
    });

    $('.js-invoice-paid').on('click', function (e) {
        e.preventDefault();

        let invoiceId = $(this).attr('data-invoice_id');

        $('body').find('#paid-invoice').find('input[name=invoice_id]').val(invoiceId);
    });

    $('.js-select-wallet-for-show-balance').on('change', function () {
        let walletId = $(this).val();

        $('.list-available-amount .alert').addClass('visually-hidden');

        $('.list-available-amount').find('[data-wallet_id="' + walletId + '"]').removeClass('visually-hidden');
    });

    $('.js-transfer-send-now').on('click', function (e) {
        e.preventDefault();

        let transferId = $(this).attr('data-transfer_id');

        $('body').find('#send-now').find('input[name=transfer_id]').val(transferId);
    });
});
