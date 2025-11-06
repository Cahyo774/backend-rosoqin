<?php

// use Illuminate\Support\Facades\Route;
// use App\Http\Controllers\Api\ProdukController;
// use App\Http\Controllers\Api\PenggunaController;
// use App\Http\Controllers\Api\KomenController;
// use App\Http\Controllers\Api\AuthController;
// use App\Http\Controllers\Api\JemputanController;
// use App\Http\Controllers\SentimentController;

// Route::post('/register', [AuthController::class, 'register']);
// Route::post('/login', [AuthController::class, 'login']);
// Route::post('/logout', [AuthController::class, 'logout']);



// Route::middleware('auth:sanctum')->group(function () {

// });

// Route::post('/logout', [AuthController::class, 'logout']);


// Route::post('/produks', [ProdukController::class, 'store']);
// Route::get('/produks/{id}', [ProdukController::class, 'show']);

// Route::apiResource('penggunas', PenggunaController::class);

// Route::apiResource('komens', KomenController::class);

// Route::apiResource('jemputans', JemputanController::class);

// Route::post('sentiment', [SentimentController::class]);

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProdukController;
use App\Http\Controllers\Api\KomenController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\JemputanController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/my-products', [ProdukController::class, 'myProducts']);
    Route::post('/produks', [ProdukController::class, 'store']);
    Route::put('/produks/{id}', [ProdukController::class, 'update']);
    Route::delete('/produks/{id}', [ProdukController::class, 'destroy']);

    Route::apiResource('komens', KomenController::class);
    Route::get('/produks/{id}/komens', [KomenController::class, 'getByProduct']);

    Route::get('/user', [AuthController::class, 'user']);
});

Route::get('/produks', [ProdukController::class, 'index']);
Route::get('/produks/{id}', [ProdukController::class, 'show']);


