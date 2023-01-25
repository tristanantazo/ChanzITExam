<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\SiteController;
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
    return view('welcome');
});


Route::get('/translate-to-word', [SiteController::class, 'numberTranslation'])->name('numbertranslate');
Route::get('/translate-to-number', [SiteController::class, 'wordTranslation'])->name('wordtranslate');
Route::get('/convert', [SiteController::class, 'converToUSD'])->name('converToUSD');