<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
    public function page(string $exposed = 'to'): Factory|View|Application
    {
        $user = auth()->user();

        $query = Invoice::query();
        if ($exposed == 'to') {
            $query->where('to_user_id', $user->id);
        } else {
            $query->where('from_user_id', $user->id);
        }

        $invoices = $query->get();

        return view('panel.invoice.index', [
            'user' => $user,
            'invoices' => $invoices,
        ]);
    }

    public function create(Request $request): RedirectResponse
    {
        $validator = Validator::make($request->all(),
            [
                'wallet_id' => 'required|integer|numeric',
                'email' => 'required|email:dns',
                'amount' => 'required|integer|numeric',
            ],
            [
                'wallet_id.required' => 'Выберите свой кошелек из списка',
                'wallet_id.integer' => 'Выберите свой кошелек из списка',
                'wallet_id.numeric' => 'Выберите свой кошелек из списка',
                'email.required' => 'Введите почту',
                'email' => 'Введите корректную почту',
                'amount.required' => 'Введите сумму',
                'amount.integer' => 'Введите целоцисленное значение',
                'amount.numeric' => 'Введите корректное значение',
            ]
        );

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        $userTo = User::query()
            ->where('email', $request->input('email'))
            ->first();

        if (!$userTo) {
            return back()->with([
                'status' => 'По данной почте пользователь не найден.',
                'class' => 'warning'
            ]);
        }

        $userFrom = auth()->user();

        $invoice = new Invoice();
        $invoice->from_user_id = $userFrom->id;
        $invoice->to_user_id = $userTo->id;
        $invoice->deposit_id = $request->input('wallet_id');
        $invoice->amount = $request->input('amount');

        if (!$invoice->save()) {
            return back()->with([
                'status' => 'Ошибка. Не удалось выставить счёт.',
                'class' => 'danger'
            ]);
        }

        return back()->with([
            'status' => 'Счет успешно выставлен.',
            'class' => 'success'
        ]);
    }

    public function paid(Request $request): RedirectResponse
    {
        $validator = Validator::make($request->all(),
            [
                'invoice_id' => 'required|integer|numeric',
                'wallet_id' => 'required|integer|numeric',
            ],
            [
                'wallet_id.required' => 'Выберите свой кошелек из списка',
                'wallet_id.integer' => 'Выберите свой кошелек из списка',
                'wallet_id.numeric' => 'Выберите свой кошелек из списка',
            ]
        );

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        $invoice = Invoice::find(
            $request->input('invoice_id')
        );

        $walletDeposit = Wallet::find(
            $invoice->deposit_id
        );

        $walletWithdraw = Wallet::find(
            $request->input('wallet_id')
        );

        if ($walletWithdraw->currency->code != $walletDeposit->currency->code) {
            return back()->with([
                'status' => 'Нельзя оплатить в другой валюте.',
                'class' => 'danger'
            ]);
        }

        if ($walletWithdraw->balance < $invoice->amount) {
            return back()->with([
                'status' => 'Недостаточно средств для оплаты.',
                'class' => 'danger'
            ]);
        }

        try {
            DB::transaction(function() use ($invoice, $walletDeposit, $walletWithdraw) {

                $user = auth()->user();

                $walletDeposit->balance += $invoice->amount;
                $walletDeposit->save();

                $transaction = new Transaction();
                $transaction->from_user_id = $user->id;
                $transaction->to_user_id = $walletDeposit->user_id;
                $transaction->from_wallet_id = $walletWithdraw->id;
                $transaction->to_wallet_id = $walletDeposit->id;
                $transaction->type = 'deposit';
                $transaction->amount = $invoice->amount;
                $transaction->confirmed = true;
                $transaction->save();

                $walletWithdraw->balance -= $invoice->amount;
                $walletWithdraw->save();

                $transaction = new Transaction();
                $transaction->to_user_id = $walletWithdraw->user_id;
                $transaction->to_wallet_id = $walletWithdraw->id;
                $transaction->type = 'withdraw';
                $transaction->amount = $invoice->amount;
                $transaction->confirmed = true;
                $transaction->save();

                $invoice->withdraw_id = $walletWithdraw->id;
                $invoice->status = 'paid';
                $invoice->save();
            });
        } catch (\Exception $e) {
            return back()->with([
                'status' => 'Ошибка.' . $e->getMessage(),
                'class' => 'danger'
            ]);
        }

        return back()->with([
            'status' => 'Счет успешно оплачен.',
            'class' => 'success'
        ]);
    }
}
