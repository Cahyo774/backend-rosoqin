<?php

use App\Models\Komen;
use App\Models\User;
use App\Models\Produk;
use Illuminate\Support\Facades\Route;

Route::get('/cekpengguna', function () {
    return User::all();
});
Route::get('/cekkomen', function () {
    return Komen::all();
});
Route::get('/cekproduk', function () {
    return Produk::all();
});
