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

Route::get('/',[App\Http\Controllers\PlayerController::class, 'TopPage'])->name('TopPage'); 


Route::get('/check', function () {
    return Inertia::render('WelcomeDefault', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});


Route::get('/dashboard', function () {
    return Inertia::render('PlayerList');
})->name('dashboard'); 

Route::prefix('login/{provider}')->where(['provider'=> 'google'])->group(function(){
    Route::get('/',[App\Http\Controllers\Auth\LoginController::class, 'redirectToProvider'])->name('sns_login.redirect');
    Route::get('/callback',[App\Http\Controllers\Auth\LoginController::class, 'handleProviderCallback'])->name('sns_login.callback');
 });

Route::get("/my-login", [App\Http\Controllers\Auth\LoginController::class, "index"])->name("myLogin");

Route::get('/data-tables', [\App\Http\Controllers\AnyController::class, 'responseDataTables'])->name('data-tables');


Route::get('/add', function () {
    return Inertia::render('DataAdd');
})->name('data.add'); 


Route::middleware(['auth:sanctum', 'verified'])->get('/i/bookmark',[App\Http\Controllers\PlayerController::class, 'bookMark'])->name('user.book'); 


Route::middleware(['auth:sanctum', 'verified'])->get('/i/added',[App\Http\Controllers\PlayerController::class, 'addedData'])->name('user.added'); 

Route::get('/{member}',[App\Http\Controllers\PlayerController::class, 'memberTop'])->name('member.top');

Route::get('/{member}/latest',[App\Http\Controllers\PlayerController::class, 'latest'])->name('member.latest');

Route::get('/{member}/player/{id}',[App\Http\Controllers\PlayerController::class, 'player'])->name('share.player'); 

Route::get('/{member}/{cate}',[App\Http\Controllers\PlayerController::class, 'memberCate'])->name('member.cate'); 

/**
*Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
*    return Inertia::render('Dashboard');
*})->name('dashboard'); 
*/
