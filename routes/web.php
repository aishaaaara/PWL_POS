<?php

use App\Http\Controllers\LevelController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\BarangController;

use Illuminate\Support\Facades\Route;

Route::get('/', [WelcomeController::class, 'index']);

Route::group(['prefix' => 'user'], function() {
    Route::get('/', [UserController::class, 'index']); //menampilkan halaman awal user
    Route::post('/list', [UserController::class, 'list']); //menampilkan data user dalam bentuk json untuk db 
    Route::get('/create', [UserController::class, 'create']); //menampilkan halaman form tambah user
    Route::post('/', [UserController::class, 'store']); //menampilkan data level baru
    Route::get('/create_ajax', [UserController::class, 'create_ajax']); //menampilkan halaman form tambah user
    Route::post('/ajax', [UserController::class, 'store_ajax']); //menyimpan data user baru ajax
    Route::get('/{id}', [UserController::class, 'show']); //menampilkan detail user
    Route::get('/{id}/edit', [UserController::class, 'edit']); //menampilkan halaman edit user
    Route::put('/{id}', [UserController::class, 'update']); //menyimapn perubahan 
    Route::get('/{id}/edit_ajax', [UserController::class, 'edit_ajax']); //menampilkan halaman edit user
    Route::put('/{id}/update_ajax', [UserController::class, 'update_ajax']);
    Route::get('/{id}/delete_ajax', [UserController::class, 'confirm_ajax']); //mnghapus data user
    Route::delete('/{id}/delete_ajax', [UserController::class, 'delete_ajax']); //mnghapus data user
    Route::delete('/{id}', [UserController::class, 'destroy']); //mnghapus data user
});

    Route::group(['prefix' => 'level'], function() {
        Route::get('/', [LevelController::class, 'index']); //menampilkan halaman awal level
        Route::post('/list', [LevelController::class, 'list']); //menampilkan data level dalam bentuk json untuk db 
        Route::get('/create', [LevelController::class, 'create']); //menampilkan halaman form tambah level
        Route::post('/', [LevelController::class, 'store']); //
        Route::get('/{id}', [LevelController::class, 'show']); //menampilkan detail level
        Route::get('/{id}/edit', [LevelController::class, 'edit']); //menampilkan halaman edit level
        Route::put('/{id}', [LevelController::class, 'update']); //menyimapn perubahan 
        Route::delete('/{id}', [LevelController::class, 'destroy']); //mnghapus data level
});

Route::group(['prefix' => 'kategori'], function() {
    Route::get('/', [KategoriController::class, 'index']); //menampilkan halaman awal Kategori
    Route::post('/list', [KategoriController::class, 'list']); //menampilkan data Kategori dalam bentuk json untuk db 
    Route::get('/create', [KategoriController::class, 'create']); //menampilkan halaman form tambah Kategori
    Route::post('/', [KategoriController::class, 'store']); //menampilkan data Kategori baru
    Route::get('/{id}', [KategoriController::class, 'show']); //menampilkan detail Kategori
    Route::get('/{id}/edit', [KategoriController::class, 'edit']); //menampilkan halaman edit Kategori
    Route::put('/{id}', [KategoriController::class, 'update']); //menyimapn perubahan 
    Route::delete('/{id}', [KategoriController::class, 'destroy']); //mnghapus data Kategori
});

Route::group(['prefix' => 'supplier'], function() {
    Route::get('/', [SupplierController::class, 'index']); //menampilkan halaman awal Supplier
    Route::post('/list', [SupplierController::class, 'list']); //menampilkan data Supplier dalam bentuk json untuk db 
    Route::get('/create', [SupplierController::class, 'create']); //menampilkan halaman form tambah Supplier
    Route::post('/', [SupplierController::class, 'store']); //menampilkan data Supplier baru
    Route::get('/{id}', [SupplierController::class, 'show']); //menampilkan detail Supplier
    Route::get('/{id}/edit', [SupplierController::class, 'edit']); //menampilkan halaman edit Supplier
    Route::put('/{id}', [SupplierController::class, 'update']); //menyimapn perubahan 
    Route::delete('/{id}', [SupplierController::class, 'destroy']); //mnghapus data Supplier
});
Route::group(['prefix' => 'barang'], function() {
    Route::get('/', [BarangController::class, 'index']); //menampilkan halaman awal Barang
    Route::post('/list', [BarangController::class, 'list']); //menampilkan data Barang dalam bentuk json untuk db 
    Route::get('/create', [BarangController::class, 'create']); //menampilkan halaman form tambah Barang
    Route::post('/', [BarangController::class, 'store']); //menampilkan data Barang baru
    Route::get('/{id}', [BarangController::class, 'show']); //menampilkan detail Barang
    Route::get('/{id}/edit', [BarangController::class, 'edit']); //menampilkan halaman edit Barang
    Route::put('/{id}', [BarangController::class, 'update']); //menyimapn perubahan 
    Route::delete('/{id}', [BarangController::class, 'destroy']); //mnghapus data Barang
});