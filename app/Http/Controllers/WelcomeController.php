<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class WelcomeController extends Controller
{
    public function page(): Factory|View|Application|RedirectResponse
    {
        if (auth()->check()) {
            return redirect()->intended(route('panel'));
        }

        return view('welcome');
    }
}
