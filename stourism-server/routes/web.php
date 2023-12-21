<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\BusinessController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\BookingController;

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
Route::get('/admin/login', [AuthController::class, 'loginView'])->name('admin.loginView');
Route::post('/admin/login', [AuthController::class, 'login'])->name('admin.login');
Route::get('/admin/register', [AuthController::class, 'registerView'])->name('admin.registerView');
Route::post('/admin/register', [AuthController::class, 'register'])->name('admin.registerPost');
Route::get('/admin/confirm-email', [AuthController::class, 'confirmEmailView'])->name('admin.confirmEmailView');
Route::post('/admin/confirm-email', [AuthController::class, 'confirmEmail'])->name('admin.confirmEmail');
Route::get('/admin/logout', [AuthController::class, 'logout'])->name('admin.logout');
Route::get('/admin/forgot-password', [AuthController::class, 'forgotPasswordView'])->name('admin.forgotPasswordView');
Route::post('/admin/forgot-password', [AuthController::class, 'forgotPassword'])->name('admin.forgotPassword');
Route::get('/admin/reset-password', [AuthController::class, 'resetPasswordView'])->name('admin.resetPasswordView');
Route::post('/admin/reset-password', [AuthController::class, 'resetPassword'])->name('admin.resetPassword');
Route::group(['middleware' => ['CheckLoginServer']], function () {
    Route::get('/admin', [AuthController::class, 'index'])->name('dashboard');
    Route::get('/get-revenue-data', [AuthController::class, 'getRevenueData']);
    Route::get('/admin/danh-muc', [CategoryController::class, 'index'])->name('category');
    Route::get('/admin/danh-muc/them-moi', [CategoryController::class, 'newCategory'])->name('category.new');
    Route::post('/admin/danh-muc/them-moi', [CategoryController::class, 'newCategoryPost'])->name('category.post');
    Route::get('/admin/danh-muc/{category_slug}/chinh-sua', [CategoryController::class, 'categoryEdit'])->name('category.edit');
    Route::post('/admin/danh-muc/{category_slug}/cap-nhat', [CategoryController::class, 'categoryUpdate'])->name('category.patch');
    Route::delete('/admin/danh-muc/{id}/xoa', [CategoryController::class, 'categoryDestroy'])->name('category.destroy');
    Route::post('/admin/danh-muc/cap-nhat-trang-thai', [CategoryController::class, 'categoryStatus'])->name('categories.change-status');

    Route::get('/admin/doanh-nghiep', [BusinessController::class, 'index'])->name('business');
    Route::get('/admin/doanh-nghiep/them-moi', [BusinessController::class, 'newBusiness'])->name('business.new');
    Route::post('/admin/doanh-nghiep/them-moi', [BusinessController::class, 'newBusinessPost'])->name('business.post');
    Route::get('/admin/doanh-nghiep/{business_slug}/chinh-sua', [BusinessController::class, 'businessEdit'])->name('business.edit');
    Route::post('/admin/doanh-nghiep/{business_slug}/cap-nhat', [BusinessController::class, 'businessUpdate'])->name('business.patch');
    Route::delete('/admin/doanh-nghiep/{id}/xoa', [BusinessController::class, 'businessDestroy'])->name('business.destroy');
    Route::post('/admin/doanh-nghiep/cap-nhat-trang-thai', [BusinessController::class, 'businessStatus'])->name('business.change-status');

    Route::get('/admin/san-pham', [ProductController::class, 'index'])->name('product');
    Route::get('/admin/san-pham/them-moi', [ProductController::class, 'newProduct'])->name('product.new');
    Route::post('/admin/san-pham/them-moi', [ProductController::class, 'newProductPost'])->name('product.post');
    Route::get('/admin/san-pham/{product_slug}/chinh-sua', [ProductController::class, 'productEdit'])->name('product.edit');
    Route::get('/admin/san-pham/{product_slug}/chi-tiet', [ProductController::class, 'productDetail'])->name('product.detail');
    Route::post('/admin/san-pham/{product_slug}/cap-nhat', [ProductController::class, 'productUpdate'])->name('product.patch');
    Route::delete('/admin/san-pham/{id}/xoa', [ProductController::class, 'productDestroy'])->name('product.destroy');
    Route::post('/admin/san-pham/cap-nhat-trang-thai', [ProductController::class, 'productStatus'])->name('product.change-status');

    Route::get('/admin/phong', [RoomController::class, 'index'])->name('room');
    Route::get('/admin/phong/them-moi', [RoomController::class, 'newRoom'])->name('room.new');
    Route::post('/admin/phong/them-moi', [RoomController::class, 'newRoomPost'])->name('room.post');
    Route::get('/admin/phong/{room_slug}/chinh-sua', [RoomController::class, 'roomEdit'])->name('room.edit');
    Route::post('/admin/phong/{room_slug}/cap-nhat', [RoomController::class, 'roomUpdate'])->name('room.patch');
    Route::delete('/admin/phong/{id}/xoa', [RoomController::class, 'roomDestroy'])->name('room.destroy');
    Route::post('/admin/phong/cap-nhat-trang-thai', [RoomController::class, 'roomStatus'])->name('room.change-status');

    Route::get('/admin/dat-cho', [BookingController::class, 'index'])->name('booking');
    Route::get('/admin/dat-cho/them-moi', [BookingController::class, 'newBooking'])->name('booking.new');
    Route::post('/admin/dat-cho/them-moi', [BookingController::class, 'newBookingPost'])->name('booking.post');
    Route::get('/admin/dat-cho/{bookingId}/chinh-sua', [BookingController::class, 'bookingEdit'])->name('booking.edit');
    Route::post('/admin/dat-cho/{bookingId}/cap-nhat', [BookingController::class, 'bookingUpdate'])->name('booking.patch');
    Route::delete('/admin/dat-cho/{bookingId}/xoa', [BookingController::class, 'bookingDestroy'])->name('booking.destroy');
    Route::post('/admin/dat-cho/cap-nhat-trang-thai', [BookingController::class, 'bookingStatus'])->name('booking.change-status');
    Route::post('/admin/dat-cho/huy-dat-phong', [BookingController::class, 'cancelBooking'])->name('booking.cancel_booking');

    Route::get('/admin/bai-viet', [\App\Http\Controllers\PostController::class, 'postAdmin'])->name('postAdmin');
    Route::get('/admin/bai-viet/them-moi', [\App\Http\Controllers\PostController::class, 'newPost'])->name('Post.new');
    Route::post('/admin/bai-viet/them-moi', [\App\Http\Controllers\PostController::class, 'newPostPost'])->name('Post.post');
    Route::get('/admin/bai-viet/{Post_slug}/chinh-sua', [\App\Http\Controllers\PostController::class, 'PostEdit'])->name('Post.edit');
    Route::post('/admin/bai-viet/{Post_slug}/cap-nhat', [\App\Http\Controllers\PostController::class, 'PostUpdate'])->name('Post.patch');
    Route::delete('/admin/bai-viet/{Post_slug}/xoa', [\App\Http\Controllers\PostController::class, 'PostDestroy'])->name('Post.destroy');
    Route::post('/admin/bai-viet/cap-nhat-trang-thai', [\App\Http\Controllers\PostController::class, 'PostStatus'])->name('Post.change-status');

    Route::get('/admin/danh-gia', [\App\Http\Controllers\RatingController::class, 'index'])->name('rating');

    Route::get('/admin/user', [\App\Http\Controllers\UserController::class, 'index'])->name('user');
    Route::post('/toggle-banner/{userId}', [\App\Http\Controllers\UserController::class, 'toggleBanner'])->name('toggle-banner');

});

Route::get('/payment-success/{bookingId}', [BookingController::class, 'bookingPayment']);
