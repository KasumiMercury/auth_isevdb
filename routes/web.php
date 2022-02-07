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
    return Inertia::render('WelcomeDefault');
})->name('TopPage'); 


Route::get('/check', function () {
    return Inertia::render('WelcomeDefault', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});


Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->name('dashboard'); 

Route::prefix('login/{provider}')->where(['provider'=> 'google'])->group(function(){
    Route::get('/',[App\Http\Controllers\Auth\LoginController::class, 'redirectToProvider'])->name('sns_login.redirect');
    Route::get('/callback',[App\Http\Controllers\Auth\LoginController::class, 'handleProviderCallback'])->name('sns_login.callback');
 });

Route::get("/my-login", [App\Http\Controllers\Auth\LoginController::class, "index"])->name("myLogin");

Route::get('/data-tables', [\App\Http\Controllers\AnyController::class, 'responseDataTables'])->name('data-tables');


Route::get('/add', function () {
    return Inertia::render('Dashboard');
})->name('data.add'); 


Route::middleware(['auth:sanctum', 'verified'])->get('/i/bookmark', function () {
    return Inertia::render('Dashboard');
})->name('user.book'); 


Route::middleware(['auth:sanctum', 'verified'])->get('/i/added', function () {
    return Inertia::render('Dashboard');
})->name('user.added'); 


Route::get('/player/{id}', function () {
    return Inertia::render('Dashboard');
})->name('share.player'); 


Route::get('/{member}/latest', function () {
    return Inertia::render('Dashboard');
})->name('member.latest');

Route::get('/{member}/{cate}', function () {
    return Inertia::render('Dashboard');
})->name('member.cate'); 

/**
*Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
*    return Inertia::render('Dashboard');
*})->name('dashboard'); 
*/
