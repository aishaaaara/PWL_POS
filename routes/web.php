<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\LevelController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\BarangController;

use Illuminate\Support\Facades\Route;

Route::pattern('id','[0-9]+'); 

Route::get('login', [AuthController::class, 'login'])->name('login');
Route::post('login', [AuthController::class, 'postlogin']);
Route::get('logout', [AuthController::class, 'logout'])->middleware('auth');

Route::middleware(['auth'])->group(function(){ 
    Route::get('/', [WelcomeController::class, 'index']);
    
    Route::middleware(['authorize:ADM'])->group(function() { 
        Route::get('/level', [LevelController::class, 'index']); //menampilkan halaman awal level
        Route::post('/level/list', [LevelController::class, 'list']); //menampilkan data level dalam bentuk json untuk db
        Route::get('/level/create', [LevelController::class, 'create']); //menampilkan halaman form tambah level
        Route::post('/level', [LevelController::class, 'store']); //
        Route::get('/level/create_ajax', [LevelController::class, 'create_ajax']); //menampilkan halaman form tambah Level
        Route::post('/level/ajax', [LevelController::class, 'store_ajax']); //menyimpan data user baru ajax
        Route::get('/level/{id}', [LevelController::class, 'show']); //menampilkan detail level
        Route::get('/level/{id}/edit', [LevelController::class, 'edit']); //menampilkan halaman edit level
        Route::put('/level/{id}', [LevelController::class, 'update']); //menyimpan perubahan 
        Route::get('/level/{id}/edit_ajax', [LevelController::class, 'edit_ajax']); //menampilkan halaman edit Level
        Route::put('/level/{id}/update_ajax', [LevelController::class, 'update_ajax']);
        Route::get('/level/{id}/delete_ajax', [LevelController::class, 'confirm_ajax']); //menghapus data Level
        Route::delete('/level/{id}/delete_ajax', [LevelController::class, 'delete_ajax']); //menghapus data user
        Route::delete('/level/{id}', [LevelController::class, 'destroy']); //menghapus data level
    });
    
    
    
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

    Route::group(['prefix' => 'kategori'], function() {
        Route::get('/', [KategoriController::class, 'index']); //menampilkan halaman awal Kategori
        Route::post('/list', [KategoriController::class, 'list']); //menampilkan data Kategori dalam bentuk json untuk db 
        Route::get('/create', [KategoriController::class, 'create']); //menampilkan halaman form tambah Kategori
        Route::post('/', [KategoriController::class, 'store']); //menampilkan data Kategori baru
        Route::get('/create_ajax', [KategoriController::class, 'create_ajax']); //menampilkan halaman form tambah Kategori
        Route::post('/ajax', [KategoriController::class, 'store_ajax']); //menyimpan data user baru ajax
        Route::get('/{id}/edit', [KategoriController::class, 'edit']); //menampilkan halaman edit Kategori
        Route::put('/{id}', [KategoriController::class, 'update']); //menyimapn perubahan 
        Route::get('/{id}/edit_ajax', [KategoriController::class, 'edit_ajax']); //menampilkan halaman edit Kategori
        Route::put('/{id}/update_ajax', [KategoriController::class, 'update_ajax']);
        Route::get('/{id}/delete_ajax', [KategoriController::class, 'confirm_ajax']); //mnghapus data Kategori
        Route::delete('/{id}/delete_ajax', [KategoriController::class, 'delete_ajax']); //mnghapus data user
        Route::delete('/{id}', [KategoriController::class, 'destroy']); //mnghapus data Kategori
    });
    
    Route::group(['prefix' => 'supplier'], function() {
        Route::get('/', [SupplierController::class, 'index']); //menampilkan halaman awal Supplier
        Route::post('/list', [SupplierController::class, 'list']); //menampilkan data Supplier dalam bentuk json untuk db 
        Route::get('/create', [SupplierController::class, 'create']); //menampilkan halaman form tambah Supplier
        Route::post('/', [SupplierController::class, 'store']); //menampilkan data Supplier baru
        Route::get('/create_ajax', [SupplierController::class, 'create_ajax']); //menampilkan halaman form tambah Supplier
        Route::post('/ajax', [SupplierController::class, 'store_ajax']); //menyimpan data user baru ajax
        Route::get('/{id}/show_ajax', [SupplierController::class, 'show_ajax']); //menampilkan detail Barang
        Route::get('/{id}/edit', [SupplierController::class, 'edit']); //menampilkan halaman edit Supplier
        Route::put('/{id}', [SupplierController::class, 'update']); //menyimapn perubahan 
        Route::get('/{id}/edit_ajax', [SupplierController::class, 'edit_ajax']); //menampilkan halaman edit Supplier
        Route::put('/{id}/update_ajax', [SupplierController::class, 'update_ajax']);
        Route::get('/{id}/delete_ajax', [SupplierController::class, 'confirm_ajax']); //mnghapus data Supplier
        Route::delete('/{id}/delete_ajax', [SupplierController::class, 'delete_ajax']); //mnghapus data user
        Route::delete('/{id}', [SupplierController::class, 'destroy']); //mnghapus data Supplier
    });
    
    Route::group(['prefix' => 'barang'], function() {
        Route::get('/', [BarangController::class, 'index']); //menampilkan halaman awal Barang
        Route::post('/list', [BarangController::class, 'list']); //menampilkan data Barang dalam bentuk json untuk db 
        Route::get('/create', [BarangController::class, 'create']); //menampilkan halaman form tambah Barang
        Route::post('/', [BarangController::class, 'store']); //menampilkan data Barang baru
        Route::get('/create_ajax', [BarangController::class, 'create_ajax']); //menampilkan halaman form tambah Barang
        Route::post('/ajax', [BarangController::class, 'store_ajax']); //menyimpan data user baru ajax
        Route::get('/{id}/show_ajax', [BarangController::class, 'show_ajax']); //menampilkan detail Barang
        Route::get('/{id}/edit', [BarangController::class, 'edit']); //menampilkan halaman edit Barang
        Route::put('/{id}', [BarangController::class, 'update']); //menyimapn perubahan 
        Route::get('/{id}/edit_ajax', [BarangController::class, 'edit_ajax']); //menampilkan halaman edit Barang
        Route::put('/{id}/update_ajax', [BarangController::class, 'update_ajax']);
        Route::get('/{id}/delete_ajax', [BarangController::class, 'confirm_ajax']); //mnghapus data Barang
        Route::delete('/{id}/delete_ajax', [BarangController::class, 'delete_ajax']); //mnghapus data user
        Route::delete('/{id}', [BarangController::class, 'destroy']); //mnghapus data Barang
    
       
    });

});