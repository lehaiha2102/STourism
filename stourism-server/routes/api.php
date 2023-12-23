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


Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/v2/reset-password', [AuthController::class, 'resetPassword'])->name('resetPassword');
    Route::post('/v2/booking', [\App\Http\Controllers\BookingController::class, 'newBookingPost']);
    Route::get('/v2/booking/get-all-my-booking', [\App\Http\Controllers\BookingController::class, 'getAllMyBooking']);
    Route::get('/v2/booking/{bookingId}', [\App\Http\Controllers\BookingController::class, 'bookingById']);
    Route::post('/v2/rating', [\App\Http\Controllers\RatingController::class, 'createRating']);
    Route::get('/v2/rating/{bookingId}', [\App\Http\Controllers\RatingController::class, 'getRatingWithBookingIf']);
    Route::post('/v2/post', [\App\Http\Controllers\PostController::class, 'post']);
    Route::get('/v2/booking/cancel/{bookingId}', [\App\Http\Controllers\BookingController::class, 'cancelBooking']);
    Route::get('/v2/my-post', [\App\Http\Controllers\PostController::class, 'getMyPost']);
    Route::post('/v2/update-profile', [\App\Http\Controllers\AuthController::class, 'updateProfilePublic']);
});
Route::post('/v2/login', [AuthController::class, 'login'])->name('login');
Route::post('/v2/register', [AuthController::class, 'register']);
Route::post('/v2/confirm-email', [AuthController::class, 'confirmEmail']);
Route::post('/v2/forgot-password', [AuthController::class, 'forgotPassword']);


Route::get('/v2/{slug}/categories', [CategoryController::class, 'getCategory']);
Route::get('/v2/categories', [CategoryController::class, 'getCategoryList']);

Route::get('/v2/{slug}/products', [ProductController::class, 'getProduct']);
Route::get('/v2/products', [ProductController::class, 'getProductList']);
Route::get('/v2/products/hotel', [ProductController::class, 'getHotelProductList']);
Route::post('/v2/rooms/search', [ProductController::class, 'searchRoom']);
Route::get('/v2/{slug}/rooms', [RoomController::class, 'getRoom']);
Route::get('/v2/rooms', [RoomController::class, 'getRoomList']);
Route::get('/v2/product/{slug}/rooms', [RoomController::class, 'getProductRooms']);
Route::get('/v2/provice', [LocationController::class, 'getProvinceList']);
Route::get('/v2/post/{id}', [\App\Http\Controllers\PostController::class, 'getPost']);
Route::get('/v2/post', [\App\Http\Controllers\PostController::class, 'index']);
Route::get('/v2/rating-for-room/{room_slug}', [\App\Http\Controllers\RatingController::class, 'getRatingWithRoomId']);