<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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

Route::get('/', function () {
    return Inertia::render('WelcomeDefault', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::prefix('login/{provider}')->where(['provider'=> 'google'])->group(function(){
    Route::get('/',[App\Http\Controllers\Auth\LoginController::class, 'redirectToProvider'])->name('sns_login.redirect');
    Route::get('/callback',[App\Http\Controllers\Auth\LoginController::class, 'handleProviderCallback'])->name('sns_login.callback');
 });

Route::get('/data-tables', [\App\Http\Controllers\AnyController::class, 'responseDataTables'])->name('data-tables');

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->name('dashboard');
