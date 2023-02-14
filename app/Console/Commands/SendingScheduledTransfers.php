<?php

namespace App\Console\Commands;

use App\Models\Transaction;
use App\Models\Transfer;
use App\Models\Wallet;
use Illuminate\Console\Command;

class SendingScheduledTransfers extends Command
{
    protected $signature = 'transfer:send';
    protected $description = 'Отправка запланированных переводов';

    public function handle()
    {
        $scheduledTransfers = Transfer::query()
            ->where('confirmed', false)
            ->whereNotNull('scheduled_date')
            ->get();

        foreach ($scheduledTransfers as $transfer) {
            $walletWithdraw = Wallet::find($transfer->from_wallet_id);

            if ($walletWithdraw->balance < $transfer->amount) {
                continue;
            }

            $walletWithdraw->balance -= $transfer->amount;
            $walletWithdraw->save();

            $transaction = new Transaction();
            $transaction->wallet_id = $transfer->from_wallet_id;
            $transaction->type = 'withdraw';
            $transaction->amount = $transfer->amount;
            $transaction->save();

            $walletDeposit = Wallet::find($transfer->to_wallet_id);
            $walletDeposit->balance += $transfer->amount;
            $walletDeposit->save();

            $transaction = new Transaction();
            $transaction->wallet_id = $transfer->to_wallet_id;
            $transaction->type = 'deposit';
            $transaction->amount = $transfer->amount;
            $transaction->save();

            $transfer->confirmed = true;
            $transfer->scheduled_date = null;
            $transfer->save();
        }
    }
}
