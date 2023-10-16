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

Route::get('/admin', [AuthController::class, 'index'])->name('dashboard');
Route::get('/admin/dang-ky', [AuthController::class, 'register'])->name('register');
Route::get('/admin/dang-nhap', [AuthController::class, 'login'])->name('login');
Route::post('/admin/dang-ky', [AuthController::class, 'registerPost']);
Route::post('/admin/dang-nhap', [AuthController::class, 'loginPost']);
Route::get('verify-email/{email}/{active_key}', [AuthController::class, 'verify'])->name('verify');// send mail is not active

Route::get('/admin/danh-muc', [CategoryController::class, 'index'])->name('category');
Route::get('/admin/danh-muc/them-moi', [CategoryController::class, 'newCategory'])->name('category.new');
Route::post('/admin/danh-muc/them-moi', [CategoryController::class, 'newCategoryPost'])->name('category.post');
Route::get('/admin/danh-muc/{category_slug}/chinh-sua', [CategoryController::class, 'categoryEdit'])->name('category.edit');
Route::post('/admin/danh-muc/{category_slug}/cap-nhat', [CategoryController::class, 'categoryUpdate'])->name('category.patch');
Route::delete('/admin/danh-muc/{category_slug}/xoa', [CategoryController::class, 'categoryDestroy'])->name('category.destroy');
Route::post('/admin/danh-muc/cap-nhat-trang-thai', [CategoryController::class, 'categoryStatus'])->name('categories.change-status');

Route::get('/admin/doanh-nghiep', [BusinessController::class, 'index'])->name('business');
Route::get('/admin/doanh-nghiep/them-moi', [BusinessController::class, 'newBusiness'])->name('business.new');
Route::post('/admin/doanh-nghiep/them-moi', [BusinessController::class, 'newBusinessPost'])->name('business.post');
Route::get('/admin/doanh-nghiep/{business_slug}/chinh-sua', [BusinessController::class, 'businessEdit'])->name('business.edit');
Route::post('/admin/doanh-nghiep/{business_slug}/cap-nhat', [BusinessController::class, 'businessUpdate'])->name('business.patch');
Route::delete('/admin/doanh-nghiep/{business_slug}/xoa', [BusinessController::class, 'businessDestroy'])->name('business.destroy');
Route::post('/admin/doanh-nghiep/cap-nhat-trang-thai', [BusinessController::class, 'businessStatus'])->name('business.change-status');

Route::get('/admin/san-pham', [ProductController::class, 'index'])->name('product');
Route::get('/admin/san-pham/them-moi', [ProductController::class, 'newProduct'])->name('product.new');
Route::post('/admin/san-pham/them-moi', [ProductController::class, 'newProductPost'])->name('product.post');
Route::get('/admin/san-pham/{product_slug}/chinh-sua', [ProductController::class, 'productEdit'])->name('product.edit');
Route::post('/admin/san-pham/{product_slug}/cap-nhat', [ProductController::class, 'productUpdate'])->name('product.patch');
Route::delete('/admin/san-pham/{product_slug}/xoa', [ProductController::class, 'productDestroy'])->name('product.destroy');
Route::post('/admin/san-pham/cap-nhat-trang-thai', [ProductController::class, 'productStatus'])->name('product.change-status');

Route::get('/admin/phong', [RoomController::class, 'index'])->name('room');
Route::get('/admin/phong/them-moi', [RoomController::class, 'newRoom'])->name('room.new');
Route::post('/admin/phong/them-moi', [RoomController::class, 'newRoomPost'])->name('room.post');
Route::get('/admin/phong/{room_slug}/chinh-sua', [RoomController::class, 'roomEdit'])->name('room.edit');
Route::post('/admin/phong/{room_slug}/cap-nhat', [RoomController::class, 'roomUpdate'])->name('room.patch');
Route::delete('/admin/phong/{room_slug}/xoa', [RoomController::class, 'roomDestroy'])->name('room.destroy');
Route::post('/admin/phong/cap-nhat-trang-thai', [RoomController::class, 'roomStatus'])->name('room.change-status');

Route::get('/admin/dat-cho', [BookingController::class, 'index'])->name('booking');
Route::get('/admin/dat-cho/them-moi', [BookingController::class, 'newBooking'])->name('booking.new');
Route::post('/admin/dat-cho/them-moi', [BookingController::class, 'newBookingPost'])->name('booking.post');
Route::get('/admin/dat-cho/{bookingId}/chinh-sua', [BookingController::class, 'bookingEdit'])->name('booking.edit');
Route::post('/admin/dat-cho/{bookingId}/cap-nhat', [BookingController::class, 'bookingUpdate'])->name('booking.patch');
Route::delete('/admin/dat-cho/{bookingId}/xoa', [BookingController::class, 'bookingDestroy'])->name('booking.destroy');
Route::post('/admin/dat-cho/cap-nhat-trang-thai', [BookingController::class, 'bookingStatus'])->name('booking.change-status');
Route::post('/admin/dat-cho/huy-dat-phong', [BookingController::class, 'cancelBooking'])->name('booking.cancel_booking');
