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
Route::get('register', [AuthController::class, 'showRegisterForm'])->name('register'); 
Route::post('register', [AuthController::class, 'register']); 

Route::middleware(['auth'])->group(function(){ 
    Route::get('/', [WelcomeController::class, 'index']);
    
    //level
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
    
    //user
    Route::middleware(['authorize:ADM,MNG'])->group(function() {
        Route::get('/user', [UserController::class, 'index']); //menampilkan halaman awal user
        Route::post('/user/list', [UserController::class, 'list']); //menampilkan data user dalam bentuk json untuk db 
         Route::get('/user/create', [UserController::class, 'create']); //menampilkan halaman form tambah user
        Route::post('/user', [UserController::class, 'store']); //menampilkan data level baru
        Route::get('/user/create_ajax', [UserController::class, 'create_ajax']); //menampilkan halaman form tambah user
        Route::post('/user/ajax', [UserController::class, 'store_ajax']); //menyimpan data user baru ajax
        Route::get('/user/{id}', [UserController::class, 'show']); //menampilkan detail user
        Route::get('/user/{id}/edit', [UserController::class, 'edit']); //menampilkan halaman edit user
        Route::put('/user/{id}', [UserController::class, 'update']); //menyimapn perubahan 
        Route::get('/user/{id}/edit_ajax', [UserController::class, 'edit_ajax']); //menampilkan halaman edit user
        Route::put('/user/{id}/update_ajax', [UserController::class, 'update_ajax']);
        Route::get('/user/{id}/delete_ajax', [UserController::class, 'confirm_ajax']); //mnghapus data user
        Route::delete('/user/{id}/delete_ajax', [UserController::class, 'delete_ajax']); //mnghapus data user
        Route::delete('/user/{id}', [UserController::class, 'destroy']); //mnghapus data user
    });

    //kategori
    Route::middleware(['authorize:ADM,MNG,STF'])->group(function() {
        Route::get('/kategori', [KategoriController::class, 'index']); //menampilkan halaman awal Kategori
        Route::post('/kategori/list', [KategoriController::class, 'list']); //menampilkan data Kategori dalam bentuk json untuk db 
        Route::get('/kategori/create', [KategoriController::class, 'create']); //menampilkan halaman form tambah Kategori
        Route::post('/kategori', [KategoriController::class, 'store']); //menampilkan data Kategori baru
        Route::get('/kategori/create_ajax', [KategoriController::class, 'create_ajax']); //menampilkan halaman form tambah Kategori
        Route::post('/kategori/ajax', [KategoriController::class, 'store_ajax']); //menyimpan data user baru ajax
        Route::get('/kategori/{id}/edit', [KategoriController::class, 'edit']); //menampilkan halaman edit Kategori
        Route::put('/kategori/{id}', [KategoriController::class, 'update']); //menyimapn perubahan 
        Route::get('/kategori/{id}/edit_ajax', [KategoriController::class, 'edit_ajax']); //menampilkan halaman edit Kategori
        Route::put('/kategori/{id}/update_ajax', [KategoriController::class, 'update_ajax']);
        Route::get('/kategori/{id}/delete_ajax', [KategoriController::class, 'confirm_ajax']); //mnghapus data Kategori
        Route::delete('/kategori/{id}/delete_ajax', [KategoriController::class, 'delete_ajax']); //mnghapus data user
        Route::delete('/kategori/{id}', [KategoriController::class, 'destroy']); //mnghapus data Kategori
    });
    
    //supplier
    Route::middleware(['authorize:ADM,MNG,STF'])->group(function() {
        Route::get('/supplier', [SupplierController::class, 'index']); //menampilkan halaman awal Supplier
        Route::post('/supplier/list', [SupplierController::class, 'list']); //menampilkan data Supplier dalam bentuk json untuk db 
        Route::get('/supplier/create', [SupplierController::class, 'create']); //menampilkan halaman form tambah Supplier
        Route::post('/supplier', [SupplierController::class, 'store']); //menampilkan data Supplier baru
        Route::get('/supplier/create_ajax', [SupplierController::class, 'create_ajax']); //menampilkan halaman form tambah Supplier
        Route::post('/supplier/ajax', [SupplierController::class, 'store_ajax']); //menyimpan data user baru ajax
        Route::get('/supplier/{id}/show_ajax', [SupplierController::class, 'show_ajax']); //menampilkan detail Barang
        Route::get('/supplier/{id}/edit', [SupplierController::class, 'edit']); //menampilkan halaman edit Supplier
        Route::put('/supplier/{id}', [SupplierController::class, 'update']); //menyimapn perubahan 
        Route::get('/supplier/{id}/edit_ajax', [SupplierController::class, 'edit_ajax']); //menampilkan halaman edit Supplier
        Route::put('/supplier/{id}/update_ajax', [SupplierController::class, 'update_ajax']);
        Route::get('/supplier/{id}/delete_ajax', [SupplierController::class, 'confirm_ajax']); //mnghapus data Supplier
        Route::delete('/supplier/{id}/delete_ajax', [SupplierController::class, 'delete_ajax']); //mnghapus data user
        Route::delete('/supplier/{id}', [SupplierController::class, 'destroy']); //mnghapus data Supplier
    });
    
    //barang
    Route::middleware(['authorize:ADM,MNG,STF,CUS'])->group(function() {
        Route::get('/barang', [BarangController::class, 'index']); //menampilkan halaman awal Barang
        Route::post('/barang/list', [BarangController::class, 'list']); //menampilkan data Barang dalam bentuk json untuk db 
        Route::get('/barang/create', [BarangController::class, 'create']); //menampilkan halaman form tambah Barang
        Route::post('/barang', [BarangController::class, 'store']); //menampilkan data Barang baru
        Route::get('/barang/create_ajax', [BarangController::class, 'create_ajax']); //menampilkan halaman form tambah Barang
        Route::post('/barang/ajax', [BarangController::class, 'store_ajax']); //menyimpan data user baru ajax
        Route::get('/barang/{id}/show_ajax', [BarangController::class, 'show_ajax']); //menampilkan detail Barang
        Route::get('/barang/{id}/edit', [BarangController::class, 'edit']); //menampilkan halaman edit Barang
        Route::put('/barang/{id}', [BarangController::class, 'update']); //menyimapn perubahan 
        Route::get('/barang/{id}/edit_ajax', [BarangController::class, 'edit_ajax']); //menampilkan halaman edit Barang
        Route::put('/barang/{id}/update_ajax', [BarangController::class, 'update_ajax']);
        Route::get('/barang/{id}/delete_ajax', [BarangController::class, 'confirm_ajax']); //mnghapus data Barang
        Route::delete('/barang/{id}/delete_ajax', [BarangController::class, 'delete_ajax']); //mnghapus data user
        Route::delete('/barang/{id}', [BarangController::class, 'destroy']); //mnghapus data Barang
    
       
    });

});