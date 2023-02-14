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
    public function page(): Factory|View|Application
    {
        $user = auth()->user();

        return view('panel.invoice.index', [
            'user' => $user,
            'invoices' => $user->issuedInvoicesTo
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

        $walletId = (int) $request->input('wallet_id');
        $email = $request->input('email');
        $amount = (int) $request->input('amount');

        $userTo = User::query()
            ->where('email', $email)
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
        $invoice->deposit_id = $walletId;
        $invoice->amount = $amount;

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

    public function pay(Request $request): RedirectResponse
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

        $invoiceId = (int) $request->input('invoice_id');
        $walletId = (int) $request->input('wallet_id');

        $invoice = Invoice::find($invoiceId);

        $walletWithdraw = Wallet::find($walletId);
        $walletDeposit = Wallet::find($invoice->deposit_id);

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
            DB::transaction(function() use ($invoice, $walletWithdraw, $walletDeposit) {
                $walletWithdraw->balance -= $invoice->amount;
                $walletWithdraw->save();

                $transaction = new Transaction();
                $transaction->wallet_id = $walletWithdraw->id;
                $transaction->type = 'withdraw';
                $transaction->amount = $invoice->amount;
                $transaction->save();

                $walletDeposit->balance += $invoice->amount;
                $walletDeposit->save();

                $transaction = new Transaction();
                $transaction->wallet_id = $walletDeposit->id;
                $transaction->type = 'deposit';
                $transaction->amount = $invoice->amount;
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
