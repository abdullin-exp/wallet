<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;

class ForgotPasswordController extends Controller
{
    public function page(): Factory|View|Application
    {
        return view('auth.forgot-password');
    }

    public function handle(Request $request): RedirectResponse
    {
        $validator = Validator::make($request->all(),
            [
                'email' => 'required|email:dns',
            ],
            [
                'email.required' => 'Введите почту',
                'email' => 'Введите корректную почту',
            ]
        );

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status === Password::RESET_LINK_SENT) {
            return back()->with([
                    'status' => 'Мы отправили ссылку для сброса пароля на указанную вами электронную почту.',
                    'class' => 'success'
                ]);
        }

        return back()->with([
            'status' => __($status),
            'class' => 'danger'
        ]);
    }
}
