<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\KeranjangController;
use App\Http\Controllers\PenjualController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\AuthPenjual;
use App\Http\Middleware\AuthUser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Auth::routes();

Route::get('/', [HomeController::class, 'index'])->name('home.index');
Route::get('/shop',[ShopController::class,'index'])->name('shop.index');
Route::get('/shop/{product_slug}',[ShopController::class,'product_details'])->name('shop.product.details');
Route::get('/cart',[KeranjangController::class,'index'])->name('keranjang.index');
Route::post('/cart/add', [KeranjangController::class, 'add_to_cart'])->name('keranjang.add');

Route::middleware([AuthUser::class])->group(function(){
    Route::get('/account-dashboard',[UserController::class,'account_dashboard'])->name('user.dashboard');
});
Route::middleware([AuthPenjual::class])->group(function(){
    Route::get('/dashboard',[PenjualController::class,'index'])->name('penjual.dashboard');
    Route::get('/dashboard/brands',[PenjualController::class,'brands'])->name('penjual.brands');
    Route::get('/dashboard/brand/add',[PenjualController::class,'add_brand'])->name('penjual.brand.add');
    Route::post('/dashboard/brand/store',[PenjualController::class,'brand_store'])->name('penjual.brand.store');
    Route::get('/dashboard/categories',[PenjualController::class,'categories'])->name('penjual.categories');
    Route::get('/dashboard/products',[PenjualController::class,'products'])->name('penjual.products');
});
