<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\MypageController;
use Illuminate\Support\Facades\Auth;

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


Route::middleware(['auth', 'verified'])->group(function () {

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

    Route::get('/mypage', [MypageController::class, 'index'])
        ->name('mypage');

    Route::get('/mypage/profile', [MypageController::class, 'editProfile'])
        ->name('mypage.profile.edit');

    Route::post('/mypage/profile', [MypageController::class, 'updateProfile'])
        ->name('mypage.profile.update');

    Route::get('/sell', [ItemController::class, 'create'])
        ->name('sell.create');

    Route::post('/sell', [ItemController::class, 'store'])
        ->name('sell.store');

    // 初回プロフィール設定（表示）
    Route::get('/profile/setup', [MypageController::class, 'setupProfile'])
        ->name('profile.setup');

    // 初回プロフィール設定（保存）
    Route::post('/profile/setup', [MypageController::class, 'storeProfile'])
        ->name('profile.store');
});


Route::get('/', function () {
    if (Auth::check()) {

        // 未認証ならメール認証へ
        if (!Auth::user()->profile) {
            return redirect()->route('profile.setup');
        }
    }

    // ここで通常の商品一覧を表示（既存の一覧処理へ）
    return app(\App\Http\Controllers\ItemController::class)->index(request());
});