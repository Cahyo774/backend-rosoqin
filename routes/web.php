<?php

use App\Models\Komen;
use App\Models\Pengguna;
use App\Models\Produk;
use Illuminate\Support\Facades\Route;

Route::get('/cekpengguna', function () {
    return Pengguna::all();
});
Route::get('/cekkomen', function () {
    return Komen::all();
});
Route::get('/cekproduk', function () {
    return Produk::all();
});
