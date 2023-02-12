<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class SignInController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function page(): Factory|View|Application|RedirectResponse
    {
        if (auth()->check()) {
            return redirect()->intended(route('panel'));
        }

        return view('auth.login');
    }

    public function handle(Request $request): RedirectResponse
    {
        $validator = Validator::make($request->all(),
            [
                'email' => 'required|email:dns',
                'password' => 'required',
            ],
            [
                'email.required' => 'Введите почту',
                'email' => 'Введите корректную почту',
                'password.required' => 'Введите пароль',
            ]
        );

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        if (auth()->attempt($validator->validated())) {
            $request->session()->regenerate();

            return redirect()->intended(route('panel'));
        }

        return back()->withErrors([
            'userNotFound' => 'Пользователь не найден',
        ]);
    }

    public function logOut(): RedirectResponse
    {
        auth()->logout();

        request()->session()->invalidate();

        request()->session()->regenerateToken();

        return redirect()->route('welcome');
    }
}
