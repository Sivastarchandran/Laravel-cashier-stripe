<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\OrderController;

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

Route::get('/', [HomeController::class, 'index']);
Route::get('/home', [HomeController::class, 'index']);

Auth::routes();
Route::middleware(['auth'])->group(function() {
    /* Shop */
    Route::get('/products', [ProductController::class,'index'])->name('product-list');
    Route::get('/checkout/{id}', [ProductController::class, 'checkout'])->name('checkout');
    /* Stripe payment */
    Route::post('/ajaxintent', [PaymentController::class,'ajaxintent'])->name('ajaxintent');
    Route::post('/purchase', [PaymentController::class,'purchase'])->name('purchase');
    Route::get('/success', [ProductController::class,'success'])->name('success');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
