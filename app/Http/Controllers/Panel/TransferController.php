<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\Transfer;
use App\Models\Wallet;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TransferController extends Controller
{
    public function page(): Factory|View|Application
    {
        $user = auth()->user();

        $sentTransfers = $user->sentTransfers;
        $receivedTransfers = $user->receivedTransfers;

        $allTransfers = $sentTransfers->merge($receivedTransfers);

        return view('panel.transfer.index', [
            'user' => $user,
            'transfers' => $allTransfers,
        ]);
    }

    public function make(Request $request): RedirectResponse
    {
        $validator = Validator::make($request->all(),
            [
                'from_wallet_id' => 'required|integer|numeric',
                'to_wallet' => 'required|uuid',
                'amount' => 'required|integer',
                'scheduled_date' => 'nullable|date',
            ],
            [
                'from_wallet_id.required' => 'Выберите кошелек для перевода',
                'from_wallet_id.integer' => 'Выберите кошелек для перевода',
                'from_wallet_id.numeric' => 'Выберите кошелек для перевода',
                'to_wallet.required' => 'Введите кошелек получателя',
                'to_wallet.uuid' => 'Введите корректный кошелек получателя',
                'amount.required' => 'Введите сумму',
                'amount.integer' => 'Введите целочисленное значение',
                'scheduled_date' => 'Введите корректную дату',
            ]
        );

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        $recipientWalletNumber = $request->input('to_wallet');
        $senderWalletId = (int) $request->input('from_wallet_id');
        $amount = (int) $request->input('amount');
        $scheduledDate = $request->input('scheduled_date');

        $recipientWallet = Wallet::query()
            ->where('number', $recipientWalletNumber)
            ->first();

        if (!$recipientWallet) {
            return back()->with([
                'status' => 'Кошелек получателя не найден.',
                'class' => 'danger'
            ]);
        }

        $senderWallet = Wallet::find($senderWalletId);

        if ($senderWallet->currency->code != $recipientWallet->currency->code) {
            return back()->with([
                'status' => 'У получателя кошелек другой валюты (' . $recipientWallet->currency->code . ').',
                'class' => 'warning'
            ]);
        }

        if ($senderWallet->balance < $amount) {
            return back()->with([
                'status' => 'Недостаточно средств для перевода.',
                'class' => 'danger'
            ]);
        }

        if ($scheduledDate != '' && strtotime($scheduledDate) <= strtotime(date('Y-m-d'))) {
            return back()->with([
                'status' => 'Необходимо запланировать будущую дату.',
                'class' => 'danger'
            ]);
        }

        try {

            DB::transaction(function () use ($senderWallet, $recipientWallet, $amount, $scheduledDate) {

                $transfer = new Transfer();
                $transfer->from_user_id = $senderWallet->user_id;
                $transfer->to_user_id = $recipientWallet->user_id;
                $transfer->from_wallet_id = $senderWallet->id;
                $transfer->to_wallet_id = $recipientWallet->id;
                $transfer->amount = $amount;

                if ($scheduledDate == '') {
                    $senderWallet->balance -= $amount;
                    $senderWallet->save();

                    $transaction = new Transaction();
                    $transaction->wallet_id = $senderWallet->id;
                    $transaction->type = 'withdraw';
                    $transaction->amount = $amount;
                    $transaction->save();

                    $recipientWallet->balance += $amount;
                    $recipientWallet->save();

                    $transaction = new Transaction();
                    $transaction->wallet_id = $recipientWallet->id;
                    $transaction->type = 'deposit';
                    $transaction->amount = $amount;
                    $transaction->save();

                    $transfer->confirmed = true;
                } else {
                    $transfer->confirmed = false;
                    $transfer->scheduled_date = $scheduledDate;
                }

                $transfer->save();

            });

            return back()->with([
                'status' => 'Успешно.',
                'class' => 'success'
            ]);

        } catch (\Exception $e) {
            report($e);

            return back()->with([
                'status' => 'Ошибка перевода. ' . $e->getMessage(),
                'class' => 'danger'
            ]);
        }
    }

    public function sendNow(Request $request): RedirectResponse
    {
        $validator = Validator::make($request->all(),
            ['transfer_id' => 'required|integer|numeric'], []
        );

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        $transferId = (int) $request->input('transfer_id');
        $transfer = Transfer::find($transferId);

        if (!$transfer) {
            return back()->with([
                'status' => 'Перевод не найден.',
                'class' => 'danger'
            ]);
        }

        try {

            DB::transaction(function () use ($transfer) {

                $walletWithdraw = Wallet::find($transfer->from_wallet_id);

                if ($walletWithdraw->balance < $transfer->amount) {
                    return back()->with([
                        'status' => 'Недостаточно средств.',
                        'class' => 'danger'
                    ]);
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

            });

            return back()->with([
                'status' => 'Успешно.',
                'class' => 'success'
            ]);

        } catch (\Exception $e) {
            report($e);

            return back()->with([
                'status' => 'Ошибка перевода. ' . $e->getMessage(),
                'class' => 'danger'
            ]);
        }
    }

    public function cancelNow(Request $request): RedirectResponse
    {
        $validator = Validator::make($request->all(),
            ['transfer_id' => 'required|integer|numeric'], []
        );

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        $transferId = (int) $request->input('transfer_id');
        $transfer = Transfer::find($transferId);

        if (!$transfer) {
            return back()->with([
                'status' => 'Перевод не найден.',
                'class' => 'danger'
            ]);
        }

        try {

            DB::transaction(function () use ($transfer) {
                $transfer->delete();
            });

            return back()->with([
                'status' => 'Успешно.',
                'class' => 'success'
            ]);

        } catch (\Exception $e) {
            report($e);

            return back()->with([
                'status' => 'Ошибка перевода. ' . $e->getMessage(),
                'class' => 'danger'
            ]);
        }
    }
}
