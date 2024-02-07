<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\RatingController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::get('/auth', function (Request $request) {
        return response()->json([$request->user()], 200);
    });

    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/user/{id}', [UserController::class, 'create']);
    Route::put('/user/{id}', [UserController::class, 'update']);

    Route::post('/book', [BookController::class, 'create']);
    Route::put('/book/{id}', [BookController::class, 'update']);
    Route::delete('/book/{id}', [BookController::class, 'destroy']);

    Route::post('/rate', [RatingController::class, 'create']);
    Route::put('/rate/{id}', [RatingController::class, 'update']);
    Route::delete('/rate/{id}', [RatingController::class, 'destroy']);
});

Route::post('/auth/{action}', [AuthController::class, 'authUser']);

Route::get('/book/{id}', [BookController::class, 'show']);
Route::get('/book', [BookController::class, 'index']);

Route::get('/rate/{id}', [RatingController::class, 'show']);
Route::get('/rating/{id}', [RatingController::class, 'rate']);
Route::get('/rate', [RatingController::class, 'index']);

Route::get('/user/{id}', [UserController::class, 'show']);
Route::get('/user', [UserController::class, 'index']);
