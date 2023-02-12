<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
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

class SignUpController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function page(): Factory|View|Application|RedirectResponse
    {
        if (auth()->check()) {
            return redirect()->intended(route('panel'));
        }

        return view('auth.sign-up');
    }

    public function handle(Request $request): RedirectResponse
    {
        $validator = Validator::make($request->all(),
            [
                'name' => 'required|string|min:2',
                'email' => 'required|email:dns|unique:users',
                'password' => 'required|confirmed',
            ],
            [
                'name.required' => 'Введите логин',
                'name.string' => 'Введите корректный логин',
                'name.min' => 'Минимальная длина 2 символа',
                'email.required' => 'Введите почту',
                'email' => 'Введите корректную почту',
                'email.unique' => 'Введенная почта используется',
                'password.required' => 'Введите пароль',
                'password.confirmed' => 'Подтвердите пароль',
            ]
        );

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        $user = User::query()->create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => bcrypt($request->input('password'))
        ]);

        auth()->login($user);

        return redirect()->intended(route('panel'));
    }
}
