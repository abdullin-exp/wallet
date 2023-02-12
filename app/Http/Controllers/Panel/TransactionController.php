<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\Wallet;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    public function page(): Factory|View|Application
    {
        $user = auth()->user();

        $transactions = Transaction::query()
            ->where('to_user_id', $user->id)
            ->get();

        return view('panel.transaction.index', [
            'user' => $user,
            'transactions' => $transactions,
        ]);
    }

    public function makeTransfer(Request $request): RedirectResponse
    {
        $validator = Validator::make($request->all(),
            [
                'from_wallet_id' => 'required|integer|numeric',
                'to_wallet' => 'required|uuid',
                'amount' => 'required|integer',
                'scheduled_at' => 'nullable|date',
            ],
            [
                'from_wallet_id.required' => 'Выберите кошелек для перевода',
                'from_wallet_id.integer' => 'Выберите кошелек для перевода',
                'from_wallet_id.numeric' => 'Выберите кошелек для перевода',
                'to_wallet.required' => 'Введите кошелек получателя',
                'to_wallet.uuid' => 'Введите корректный кошелек получателя',
                'amount.required' => 'Введите сумму',
                'amount.integer' => 'Введите целочисленное значение',
                'scheduled_at' => 'Введите корректную дату',
            ]
        );

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        $walletTo = Wallet::query()
            ->where('number', $request->input('to_wallet'))
            ->first();

        if (!$walletTo) {
            return back()->with([
                'status' => 'Кошелек получателя не найден.',
                'class' => 'danger'
            ]);
        }

        $walletFrom = Wallet::find(
            $request->input('from_wallet_id')
        );

        if ($walletFrom->currency->code != $walletTo->currency->code) {
            return back()->with([
                'status' => 'У получателя кошелек другой валюты (' . $walletTo->currency->code . ').',
                'class' => 'warning'
            ]);
        }

        if ($walletFrom->balance < $request->input('amount')) {
            return back()->with([
                'status' => 'Недостаточно средств для перевода.',
                'class' => 'danger'
            ]);
        }

        if ($request->input('scheduled_at') != '' && strtotime($request->input('scheduled_at')) <= strtotime(date('Y-m-d'))) {
            return back()->with([
                'status' => 'Необходимо запланировать будущую дату.',
                'class' => 'danger'
            ]);
        }

        try {
            DB::transaction(function () use ($walletFrom, $walletTo, $request) {

                $transactionFrom = new Transaction();
                $transactionFrom->to_user_id = $walletFrom->user_id;
                $transactionFrom->to_wallet_id = $walletFrom->id;
                $transactionFrom->type = 'withdraw';
                $transactionFrom->amount = $request->input('amount');

                $transactionTo = new Transaction();
                $transactionTo->from_user_id = $walletFrom->user_id;
                $transactionTo->to_user_id = $walletTo->user_id;
                $transactionTo->from_wallet_id = $walletFrom->id;
                $transactionTo->to_wallet_id = $walletTo->id;
                $transactionTo->type = 'deposit';
                $transactionTo->amount = $request->input('amount');

                if ($request->input('scheduled_at') == '') {
                    $walletFrom->balance -= $request->input('amount');
                    $walletFrom->save();

                    $transactionFrom->confirmed = true;

                    $walletTo->balance += $request->input('amount');
                    $walletTo->save();

                    $transactionTo->confirmed = true;
                } else {
                    $transactionFrom->confirmed = false;
                    $transactionFrom->scheduled_at = $request->input('scheduled_at');

                    $transactionTo->confirmed = false;
                    $transactionTo->scheduled_at = $request->input('scheduled_at');
                }

                $transactionFrom->save();
                $transactionTo->save();

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

        try {

            DB::transaction(function () use ($request) {

                $transferWithdraw = Transaction::find(
                    $request->input('transfer_id')
                );

                $transferDeposit = Transaction::query()
                    ->select('*')
                    ->where('from_wallet_id', $transferWithdraw->to_wallet_id)
                    ->where('type', 'deposit')
                    ->where('amount', $transferWithdraw->amount)
                    ->where('confirmed', false)
                    ->whereNotNull('scheduled_at')
                    ->where('scheduled_at', date('Y-m-d', strtotime($transferWithdraw->scheduled_at)))
                    ->first();

                $walletWithdraw = Wallet::find($transferWithdraw->to_wallet_id);
                $walletWithdraw->balance -= $transferWithdraw->amount;
                $walletWithdraw->save();

                $walletDeposit = Wallet::find($transferDeposit->to_wallet_id);
                $walletDeposit->balance += $transferWithdraw->amount;
                $walletDeposit->save();

                $transferWithdraw->confirmed = true;
                $transferWithdraw->scheduled_at = '';
                $transferWithdraw->save();

                $transferDeposit->confirmed = true;
                $transferDeposit->scheduled_at = '';
                $transferDeposit->save();

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

        try {

            DB::transaction(function () use ($request) {

                $transferWithdraw = Transaction::find(
                    $request->input('transfer_id')
                );

                $transferDeposit = Transaction::query()
                    ->select('*')
                    ->where('from_wallet_id', $transferWithdraw->to_wallet_id)
                    ->where('type', 'deposit')
                    ->where('amount', $transferWithdraw->amount)
                    ->where('confirmed', false)
                    ->whereNotNull('scheduled_at')
                    ->where('scheduled_at', date('Y-m-d', strtotime($transferWithdraw->scheduled_at)))
                    ->first();

                $transferWithdraw->delete();
                $transferDeposit->delete();

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
