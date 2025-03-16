<?php

// use App\Http\Controllers\LevelController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\LevelController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\SuplierController;
use App\Models\UserModel;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});


// Route::get('/level', [LevelController::class, 'index']);
// Route::get('/kategori', [KategoriController::class, 'index']);
// Route::get('/user', [UserController::class, 'index']);
// Route::get('/user/tambah', [UserController::class, 'tambah']);
// Route::post('/user/tambah_simpan', [UserController::class, 'tambah_simpan']);
// Route::get('/user/ubah/{id}', [UserController::class, 'ubah']);
// Route::put('/user/ubah_simpan/{id}', [UserController::class, 'ubah_simpan']);
// Route::get('/user/hapus/{id}', [UserController::class, 'hapus']);

Route::get('/', [WelcomeController::class, 'index']);

Route::group(['prefix' => 'user'], function () {
    Route::get('/', [UserController::class, 'index']); // Menampilkan halaman user
    Route::post('/list', [UserController::class, 'list']); // Menampilkan data user dalam bentuk JSON untuk DataTables
    Route::get('/create', [UserController::class, 'create']); // Menampilkan form tambah user
    Route::post('/', [UserController::class, 'store']); // Menyimpan data user baru
    Route::get('/{id}', [UserController::class, 'show']); // Menampilkan detail user
    Route::get('/{id}/edit', [UserController::class, 'edit']); // Menampilkan form edit user
    Route::put('/{id}', [UserController::class, 'update']); // Menyimpan perubahan data user
    Route::delete('/{id}', [UserController::class, 'destroy']); // Menghapus user
});

Route::group(['prefix' => 'level'], function () {
    Route::get('/', [LevelController::class, 'index']); // Menampilkan halaman level
    Route::post('/list', [LevelController::class, 'list']); // Menampilkan data level dalam bentuk JSON untuk DataTables
    Route::get('/create', [LevelController::class, 'create']); // Menampilkan form tambah level
    Route::post('/', [LevelController::class, 'store']); // Menyimpan data level baru
    Route::get('/{id}', [LevelController::class, 'show']); // Menampilkan detail level
    Route::get('/{id}/edit', [LevelController::class, 'edit']); // Menampilkan form edit level
    Route::put('/{id}', [LevelController::class, 'update']); // Menyimpan perubahan data level
    Route::delete('/{id}', [LevelController::class, 'destroy']); // Menghapus level
});

Route::group(['prefix' => 'kategori'], function () {
    Route::get('/', [KategoriController::class, 'index']); // Menampilkan halaman kategori
    Route::post('/list', [KategoriController::class, 'list']); // Menampilkan data kategori dalam bentuk JSON untuk DataTables
    Route::get('/create', [KategoriController::class, 'create']); // Menampilkan form tambah kategori
    Route::post('/', [KategoriController::class, 'store']); // Menyimpan data kategori baru
    Route::get('/{id}', [KategoriController::class, 'show']); // Menampilkan detail kategori
    Route::get('/{id}/edit', [KategoriController::class, 'edit']); // Menampilkan form edit kategori
    Route::put('/{id}', [KategoriController::class, 'update']); // Menyimpan perubahan data kategori
    Route::delete('/{id}', [KategoriController::class, 'destroy']); // Menghapus kategori
});

Route::group(['prefix' => 'barang'], function () {
    Route::get('/', [BarangController::class, 'index']); // Menampilkan halaman daftar barang
    Route::post('/list', [BarangController::class, 'list']); // Menampilkan data barang dalam bentuk JSON untuk DataTables
    Route::get('/create', [BarangController::class, 'create']); // Menampilkan form tambah barang
    Route::post('/', [BarangController::class, 'store']); // Menyimpan data barang baru
    Route::get('/{id}', [BarangController::class, 'show']); // Menampilkan detail barang
    Route::get('/{id}/edit', [BarangController::class, 'edit']); // Menampilkan form edit barang
    Route::put('/{id}', [BarangController::class, 'update']); // Menyimpan perubahan data barang
    Route::delete('/{id}', [BarangController::class, 'destroy']); // Menghapus barang
});

Route::group(['prefix' => 'suplier'], function () {
    Route::get('/', [SuplierController::class, 'index']); // Menampilkan halaman daftar suplier
    Route::post('/list', [SuplierController::class, 'list']); // Menampilkan data suplier dalam bentuk JSON untuk DataTables
    Route::get('/create', [SuplierController::class, 'create']); // Menampilkan form tambah suplier
    Route::post('/', [SuplierController::class, 'store']); // Menyimpan data suplier baru
    Route::get('/{id}', [SuplierController::class, 'show']); // Menampilkan detail suplier
    Route::get('/{id}/edit', [SuplierController::class, 'edit']); // Menampilkan form edit suplier
    Route::put('/{id}', [SuplierController::class, 'update']); // Menyimpan perubahan data suplier
    Route::delete('/{id}', [SuplierController::class, 'destroy']); // Menghapus suplier
});
