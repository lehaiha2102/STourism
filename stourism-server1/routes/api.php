<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
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

Route::group([

    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {

    Route::post('/login', [AuthController::class,'login']);
    Route::post('/register', [AuthController::class,'register']);
    Route::post('/logout', [AuthController::class,'logout']);
    Route::post('/refresh', [AuthController::class,'refresh']);
    Route::post('/me', [AuthController::class,'me']);
    Route::patch('/user/update', [AuthController::class,'update']);
});

Route::get('/categories', [\App\Http\Controllers\CategoryController::class, 'getCategoryList']);
Route::get('/categories/{category_slug}', [\App\Http\Controllers\CategoryController::class, 'getCategory']);
Route::post('/categories/create', [\App\Http\Controllers\CategoryController::class, 'createCategory']);
Route::patch('/categories/{category_slug}/update', [\App\Http\Controllers\CategoryController::class, 'updateCategory']);
Route::delete('/categories/{category_slug}/delete', [\App\Http\Controllers\CategoryController::class, 'deleteCategory']);
