<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProdukController;
use App\Http\Controllers\Api\PenggunaController;
use App\Http\Controllers\Api\KomenController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\JemputanController;
use App\Http\Controllers\SentimentController;

// AUTH
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);


// Protected routes (harus login)
Route::middleware('auth:sanctum')->group(function () {

});

Route::post('/logout', [AuthController::class, 'logout']);

// Produk CRUD
Route::apiResource('produks', ProdukController::class);

// Pengguna CRUD
Route::apiResource('penggunas', PenggunaController::class);

// Komen CRUD
Route::apiResource('komens', KomenController::class);

Route::apiResource('jemputans', JemputanController::class);

Route::post('sentiment', [SentimentController::class]);
