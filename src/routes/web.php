<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\PurchaseController;

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

Route::get('/', [ItemController::class, 'index']);

Route::get('/item/{item_id}', [ItemController::class, 'show'])
    ->name('items.show');

Route::post('/item/{item_id}/favorite', [ItemController::class, 'toggleFavorite'])
    ->middleware('auth')
    ->name('items.favorite');

Route::post('/item/{item_id}/comment', [ItemController::class, 'storeComment'])
    ->middleware('auth')
    ->name('items.comment');


Route::middleware('auth')->group(function () {

    Route::get('/purchase/{item}', [PurchaseController::class, 'confirm'])
        ->name('purchase.confirm');

    Route::post('/purchase/{item}', [PurchaseController::class, 'store'])
        ->name('purchase.store');

    Route::get('/purchase/{item}/payment', [PurchaseController::class, 'editPayment'])
        ->name('purchase.payment.edit');

    Route::post('/purchase/{item}/payment', [PurchaseController::class, 'updatePayment'])
        ->name('purchase.payment.update');

    Route::get('/purchase/address/{item}', [PurchaseController::class, 'editAddress'])
        ->name('purchase.address.edit');

    Route::post('/purchase/address/{item}', [PurchaseController::class, 'updateAddress'])
        ->name('purchase.address.update');
});
