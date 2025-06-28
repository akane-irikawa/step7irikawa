<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Api\PurchaseController;

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
Auth::routes(); 

Route::get('/', function () {
    return view('welcome');    
});

Route::get('/productList/search', [ProductController::class, 'search'])->name('productList.search');

// 商品関連のルート設定
Route::middleware(['auth'])->group(function () {
    Route::get('/productList', [ProductController::class, 'index'])->name('productList.index');
    Route::get('/productList/create', [ProductController::class, 'create'])->name('productList.create');
    Route::post('/productList', [ProductController::class, 'store'])->name('productList.store');
    Route::get('/productList/{id}', [ProductController::class, 'show'])->name('productList.show');
    Route::get('/productList/{id}/edit', [ProductController::class, 'edit'])->name('productList.edit'); // 商品編集
    Route::put('/productList/{id}', [ProductController::class, 'update'])->name('productList.update'); // 商品更新
    Route::delete('/productList/{id}', [ProductController::class, 'destroy'])->name('productList.destroy');
});
// ログイン後のリダイレクト先を設定
Route::get('/home', function () {
    return redirect()->route('productList.index');  // 商品一覧画面にリダイレクト
})->name('home')->middleware('auth');

Route::post('/purchase', [PurchaseController::class, 'purchase']);

