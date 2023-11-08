<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\RoomController;
use App\Http\Controllers\Api\LocationController;
use App\Http\Controllers\AuthController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/v2/login', [AuthController::class, 'login'])->name('login');
Route::post('/v2/register', [AuthController::class, 'register'])->name('registerPost');
Route::post('/v2/confirm-email', [AuthController::class, 'confirmEmail'])->name('confirmEmail');
Route::post('/v2/forgot-password', [AuthController::class, 'forgotPassword'])->name('forgotPassword');
Route::post('/v2/reset-password', [AuthController::class, 'resetPassword'])->name('resetPassword');

Route::get('/v2/{slug}/categories', [CategoryController::class, 'getCategory']);
Route::get('/v2/categories', [CategoryController::class, 'getCategoryList']);

Route::get('/v2/{slug}/products', [ProductController::class, 'getProduct']);
Route::get('/v2/products/food', [ProductController::class, 'getFoodProductList']);
Route::get('/v2/products/hotel', [ProductController::class, 'getHotelProductList']);
Route::post('/v2/rooms/search', [ProductController::class, 'searchRoom']);
Route::get('/v2/{slug}/rooms', [RoomController::class, 'getRoom']);
Route::get('/v2/rooms', [RoomController::class, 'getRoomList']);
Route::get('/v2/product/{id}/rooms', [RoomController::class, 'getProductRooms']);
Route::get('/v2/provice', [LocationController::class, 'getProvinceList']);
