<?php

declare(strict_types=1);

use App\Livewire\AddBankAccount;
use App\Livewire\ShowBankAccounts;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', static fn () => view('welcome'));

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', static fn () => view('dashboard'))->name('dashboard');
    Route::get('/bank-accounts', ShowBankAccounts::class)->name('bank-accounts');
    Route::get('/add-bank-account', AddBankAccount::class)->name('add-bank-account');
    Route::get('/update-bank-account/{id}', ShowBankAccounts::class)->name('update-bank-account');
});
