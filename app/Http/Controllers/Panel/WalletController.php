<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Currency;
use App\Models\Transaction;
use App\Models\Wallet;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class WalletController extends Controller
{
    public function page(): Factory|View|Application
    {
        $user = auth()->user();
        $currencies = Currency::all();

        return view('panel.wallet.index', [
            'user' => $user,
            'wallets' => $user->wallets,
            'currencies' => $currencies
        ]);
    }

    public function create(Request $request): RedirectResponse
    {
        $validator = Validator::make($request->all(),
            [
                'currency_id' => 'integer|numeric',
            ],
            [
                'currency_id.integer' => 'Выберите валюту из списка',
                'currency_id.numeric' => 'Выберите валюту из списка',
            ]
        );

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        $user = auth()->user();

        $wallet = new Wallet();
        $wallet->user_id = $user->id;
        $wallet->currency_id = $request->input('currency_id');
        $wallet->number = (string) Str::uuid();
        $wallet->save();

        return back()->with([
            'status' => 'Кошелек создан.',
            'class' => 'success'
        ]);
    }

    public function deposit(Request $request): RedirectResponse
    {
        $validator = Validator::make($request->all(),
            [
                'wallet_id' => 'integer|numeric',
                'amount' => 'integer|numeric',
            ],
            [
                'amount.integer' => 'Введите целочисленное значение',
                'amount.numeric' => 'Введите целочисленное значение',
            ]
        );

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::transaction(function () use ($request) {
                $user = auth()->user();

                $wallet = Wallet::find(
                    $request->input('wallet_id')
                );

                $wallet->balance += $request->input('amount');
                $wallet->save();

                $transaction = new Transaction();
                $transaction->from_user_id = $user->id;
                $transaction->to_user_id = $user->id;
                $transaction->to_wallet_id = $wallet->id;
                $transaction->type = 'deposit';
                $transaction->amount = $request->input('amount');
                $transaction->confirmed = true;
                $transaction->save();
            });

            return back()->with([
                'status' => 'Баланс успешно пополнен.',
                'class' => 'success'
            ]);

        } catch (\Exception $e) {

            report($e);

            return back()->with([
                'status' => 'Ошибка. Не удалось пополнить баланс.',
                'class' => 'success'
            ]);
        }
    }
}
