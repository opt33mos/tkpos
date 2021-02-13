<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::apiResources([
    'user' => 'App\Http\Controllers\API\UserController',
    'category' => 'App\Http\Controllers\API\CategoryController',
    'size' => 'App\Http\Controllers\API\SizeController',
    'product' => 'App\Http\Controllers\API\ProductController',
    'transaction' => 'App\Http\Controllers\API\TransactionController',
]);

Route::middleware('auth:api')->group(function () {
   Route::get('/user', ['App\Http\Controllers\API\UserController', 'index']);
   Route::get('/category', ['App\Http\Controllers\API\CategoryController', 'index']);
   Route::get('/size', ['App\Http\Controllers\API\SizeController', 'index']);
   Route::get('/product', ['App\Http\Controllers\API\ProductController', 'index']);
   Route::get('/transaction', ['App\Http\Controllers\API\TransactionController', 'index']);
});



