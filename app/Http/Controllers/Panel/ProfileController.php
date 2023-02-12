<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserDetail;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    public function page(): Factory|View|Application|RedirectResponse
    {
        $user = auth()->user();

        return view('panel.profile.index', [
            'user' => $user
        ]);
    }

    public function save(Request $request): RedirectResponse
    {
        $validator = Validator::make($request->all(),
            [
                'user_id' => 'integer|numeric',
                'last_name' => 'nullable|string|min:2',
                'first_name' => 'nullable|string|min:2',
                'patr_name' => 'nullable|string|min:2',
                'birth_date' => 'nullable|date',
                'gender' => 'nullable|string|max:1',
            ],
            [
                'last_name.string' => 'Введите корректную фамилию',
                'first_name.string' => 'Введите корректное имя',
                'patr_name.string' => 'Введите корректное отчество',
                'birth_date.date' => 'Введите корректную дату рождения',
            ]
        );

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        UserDetail::query()->updateOrCreate(
            [
                'user_id' => $request->input('user_id')
            ],
            [
                'user_id' => $request->input('user_id'),
                'last_name' => $request->input('last_name'),
                'first_name' => $request->input('first_name'),
                'patr_name' => $request->input('patr_name'),
                'birth_date' => $request->input('birth_date'),
                'gender' => $request->input('gender')
            ]
        );

        return back()->with([
            'status' => 'Изменения сохранены.',
            'class' => 'success'
        ]);
    }
}
