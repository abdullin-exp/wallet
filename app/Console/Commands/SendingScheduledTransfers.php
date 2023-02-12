<?php

namespace App\Console\Commands;

use App\Models\Transaction;
use App\Models\Wallet;
use Illuminate\Console\Command;

class SendingScheduledTransfers extends Command
{
    protected $signature = 'transfer:send';
    protected $description = 'Отправка запланированных переводов';

    public function handle()
    {
        $scheduledTransfers = Transaction::query()
            ->where('confirmed', false)
            ->whereNotNull('scheduled_at')
            ->get();

        foreach ($scheduledTransfers as $transfer) {
            if ($transfer->type == 'withdraw') {
                $wallet = Wallet::find($transfer->to_wallet_id);
                $wallet->balance -= $transfer->amount;
            } else {
                $wallet = Wallet::find($transfer->to_wallet_id);
                $wallet->balance += $transfer->amount;
            }

            $wallet->save();
            $transfer->confirmed = true;
            $transfer->scheduled_at = null;
            $transfer->save();
        }
    }
}
