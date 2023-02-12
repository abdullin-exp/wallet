<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;

class ResetPasswordController extends Controller
{
    public function page(string $token): Factory|View|Application
    {
        return view('auth.reset-password', [
            'token' => $token
        ]);
    }

    public function handle(Request $request): RedirectResponse
    {
        $validator = Validator::make($request->all(),
            [
                'email' => 'required|email:dns',
                'password' => 'required|confirmed',
            ],
            [
                'email.required' => 'Введите почту',
                'email' => 'Введите корректную почту',
                'password.required' => 'Введите пароль',
                'password.confirmed' => 'Подтвердите пароль',
            ]
        );

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => bcrypt($password)
                ])->setRememberToken(str()->random(60));

                $user->save();
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return redirect()->route('login')->with([
                'status' => 'Пароль успешно сброшен.',
                'class' => 'success'
            ]);
        }

        return back()->with([
            'status' => __($status),
            'class' => 'danger'
        ]);
    }
}
