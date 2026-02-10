<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;

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
Route::get('/', [ItemController::class, 'index']);

Route::get('/item/{item_id}', [ItemController::class, 'show'])->name('items.show');

Route::post('/item/{item_id}/favorite', [ItemController::class, 'toggleFavorite'])
    ->middleware('auth')
    ->name('items.favorite');

Route::post('/item/{item_id}/comment', [ItemController::class, 'storeComment'])
    ->middleware('auth')
    ->name('items.comment');