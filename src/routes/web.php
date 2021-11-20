<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\RulesController;
use App\Http\Controllers\RegistrationLoginController;
use App\Http\Controllers\GameController;

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

Route::get('/', [IndexController::class, 'index'])->name('index');
Route::post('/', [RegistrationLoginController::class, 'findAction'])->name('reglog');

Route::get('/rules', [RulesController::class, 'rules'])->name('rules');

Route::get('/account', [AccountController::class, 'account'])->name('account');
Route::post('/account', [AccountController::class, 'showAPI'])->name('showAPI');

Route::post('/game', [GameController::class, 'saveField']);
