<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Currency;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class PanelController extends Controller
{
    public function page(): Factory|View|Application|RedirectResponse
    {
        $user = auth()->user();

        return view('panel.index', [
            'user' => $user
        ]);
    }
}
