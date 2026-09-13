<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ShopController;

Route::get('/chat/policies', [ChatController::class, 'policies']);
Route::get('/', fn() => redirect('/shop'));
Route::get('/register',[AuthController::class,'registerForm']);
Route::post('/register',[AuthController::class,'register']);

Route::get('/login',[AuthController::class,'loginForm']);
Route::post('/login',[AuthController::class,'login']);
Route::get('/logout',[AuthController::class,'logout']);

Route::get('/shop',[ShopController::class,'index']);
Route::get('/product/{slug}',[ShopController::class,'product']);
Route::get('/discate/{id}',[ShopController::class,'productCategory']);
Route::get('/latestpro',[ShopController::class,'newproduct']);

Route::post('/cart/add/{id}',[ShopController::class,'addToCart'])->middleware('role:customer');
Route::get('/remove/{id}',[ShopController::class,'removeproduct'])->middleware('role:customer');
Route::get('/cart',[ShopController::class,'cart'])->middleware('role:customer');
Route::post('/cart/update/{id}',[ShopController::class,'updateCart'])->middleware('role:customer');
Route::get('/checkout',[ShopController::class,'checkout'])->middleware('role:customer');
Route::post('/order',[ShopController::class,'placeOrder'])->middleware('role:customer');
Route::get('/orders',[ShopController::class,'orders'])->middleware('role:customer');

Route::prefix('admin')->middleware('role:admin')->group(function(){
    Route::get('/dashboard',[AdminController::class,'dashboard']);
    Route::get('/product',[AdminController::class,'products']);
    Route::get('/showcategory',[AdminController::class, 'showCategory']);
    Route::post('/addcategories',[AdminController::class, 'addCate']);
    Route::post('/deletecate',[AdminController::class, 'deleteCate']);
    Route::post('/products',[AdminController::class,'storeProduct']);
    Route::post('/updateproducts/{id}',[AdminController::class, 'updateProduct']);
    Route::get('/deleteimage/{id}',[AdminController::class, 'deleteImage']);
    Route::post('/changecate/{id}',[AdminController::class, 'changeCate']);
    Route::post('/addcate',[AdminController::class, 'addCategory']);

    Route::get('/orders',[AdminController::class,'orders']);
    Route::get('/updateproduct/{id}',[AdminController::class, 'showUpdateProduct']);
    Route::post('/orders/{id}/status',[AdminController::class,'updateOrderStatus']);
});