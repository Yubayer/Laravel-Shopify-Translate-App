<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\TestController;
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

Route::get('/', [TestController::class, 'index'])->middleware(['verify.shopify'])->name('home');
Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Route::post('/translate', [TestController::class, 'translate'])->name('translate');
Route::post('/translate', [TestController::class, 'translate'])->middleware(['verify.shopify'])->name('translate');
