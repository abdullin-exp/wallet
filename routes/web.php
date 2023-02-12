<?php

use App\Http\Controllers\Panel\InvoiceController;
use App\Http\Controllers\Panel\ProfileController;
use App\Http\Controllers\Panel\TransactionController;
use App\Http\Controllers\Panel\WalletController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\Panel\PanelController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\SignInController;
use App\Http\Controllers\Auth\SignUpController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [WelcomeController::class, 'page'])->name('welcome');

Route::controller(SignInController::class)->group(function () {
    Route::get('/login', 'page')->name('login');
    Route::post('/login', 'handle')
        ->name('login.handle');
    Route::delete('/logout', 'logOut')->name('logOut');
});

Route::controller(SignUpController::class)->group(function () {
    Route::get('/sign-up', 'page')->name('register');

    Route::post('/sign-up', 'handle')
        ->name('register.handle');
});

Route::controller(ForgotPasswordController::class)->group(function () {
    Route::get('/forgot-password', 'page')
        ->name('forgot');

    Route::post('/forgot-password', 'handle')
        ->name('forgot.handle');
});

Route::controller(ResetPasswordController::class)->group(function () {
    Route::get('/reset-password/{token}', 'page')
        ->name('password.reset');

    Route::post('/reset-password', 'handle')
        ->name('password-reset.handle');
});

Route::group(['middleware' => 'auth', 'namespace' => 'Panel', 'prefix' => 'panel'], function () {
    Route::get('/', [PanelController::class, 'page'])->name('panel');

    Route::get('/wallets', [WalletController::class, 'page'])->name('panel-wallets');
    Route::post('/wallets/create', [WalletController::class, 'create'])->name('panel-wallets.create');
    Route::post('/wallets/deposit', [WalletController::class, 'deposit'])->name('panel-wallets.deposit');

    Route::get('/profile', [ProfileController::class, 'page'])->name('panel-profile');
    Route::post('/profile', [ProfileController::class, 'save'])->name('panel-profile.save');

    Route::get('/transactions', [TransactionController::class, 'page'])->name('panel-transactions');
    Route::post('/transactions/make-transfer', [TransactionController::class, 'makeTransfer'])->name('panel-transactions.makeTransfer');
    Route::post('/transactions/send-now', [TransactionController::class, 'sendNow'])->name('panel-transactions.sendNow');
    Route::post('/transactions/cancel-now', [TransactionController::class, 'cancelNow'])->name('panel-transactions.cancelNow');

    Route::get('/invoices/{exposed?}', [InvoiceController::class, 'page'])->name('panel-invoices');
    Route::post('/invoices/create', [InvoiceController::class, 'create'])->name('panel-invoices.create');
    Route::post('/invoices/paid', [InvoiceController::class, 'paid'])->name('panel-invoices.paid');

});
