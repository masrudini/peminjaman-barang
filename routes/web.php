<?php

use App\Models\Ruangan;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\QrCodeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RuanganController;
use App\Http\Controllers\JaringanController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\KategoriBarangController;

// ===============================
// Authentication Routes
// ===============================
Route::controller(AuthController::class)->group(function () {
    Route::get('register', 'register')->name('register');
    Route::post('register', 'registerSave')->name('register.save');
    Route::get('login', 'login')->name('login');
    Route::post('login', 'loginAction')->name('login.action');
    Route::post('logout', 'logout')->middleware('auth')->name('logout');
});

// ===============================
// Public Routes
// ===============================
Route::get('/', [PeminjamanController::class, 'create'])->name('peminjaman.create');
Route::post('/peminjaman/store', [PeminjamanController::class, 'store'])->name('peminjaman.store');
Route::get('/barang/{id}', [BarangController::class, 'show'])->name('barang.show');
Route::get('/jaringan', [JaringanController::class, 'index'])->name('jaringan.index');
Route::post('jaringan', [JaringanController::class, 'store'])->name('jaringan.store');
Route::get('/peminjaman/ruangan', [PeminjamanController::class, 'ruangan'])->name('peminjaman.ruangan');
Route::post('/peminjaman/ruangan/store', [PeminjamanController::class, 'storeRuangan'])->name('peminjaman.ruangan.store');

// ===============================
// Authenticated Routes
// ===============================
Route::middleware('auth')->group(function () {

    // Dashboard
    Route::get('dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // User Profile and Management
    Route::get('/show', [AuthController::class, 'profile'])->name('show');
    Route::get('/manajemen', [AuthController::class, 'manajemen'])->name('admin.manajemen_akun');

    // Peminjaman Routes
    Route::get('/admin/peminjaman', [PeminjamanController::class, 'index'])->name('peminjaman.index');
    Route::get('/peminjaman/{id}/download-pdf', [PeminjamanController::class, 'downloadPdf'])->name('peminjaman.download-pdf'); // Specific route first
    Route::delete('/peminjaman/{id}', [PeminjamanController::class, 'delete'])->name('peminjaman.delete');
    Route::get('/peminjaman/edit/{id}', [PeminjamanController::class, 'edit'])->name('peminjaman.edit');
    Route::put('/peminjaman/update/{id}', [PeminjamanController::class, 'update'])->name('peminjaman.update');
    Route::put('/peminjaman/{id}/kembalikan', [PeminjamanController::class, 'kembalikanBarang'])->name('peminjaman.kembalikan');
    Route::post('/peminjaman/{id}', [PeminjamanController::class, 'updateKeterangan'])->name('peminjaman.update-keterangan');

    // ===============================
    // Barang Routes
    // ===============================
    Route::controller(BarangController::class)->group(function () {
        Route::get('/barang', 'index')->name('barang.index');
        Route::get('/barang-create', 'create')->name('barang-create');
        Route::post('/barang', 'store')->name('barang.store');

        Route::get('/barang/{id}/edit', 'edit')->name('barang.edit');
        Route::put('/barang/{id}', 'update')->name('barang.update');
        Route::delete('/barang/{id}', 'destroy')->name('barang.destroy');
        Route::post('/barang/print-selected', 'printSelected')->name('barang.printSelected');
        Route::get('/barang/print', 'printAll')->name('barang.printAll');
        Route::get('/barang/toggle-status/{id}', 'toggleStatus')->name('barang.toggleStatus');
    });

    // ===============================
    // User Routes
    // ===============================
    Route::controller(UserController::class)->group(function () {
        Route::get('/user', 'index')->name('user.index');
        Route::post('/user', 'store')->name('user.store');
        Route::put('/user/{id}', 'update')->name('user.update');
        Route::delete('/user/{id}', 'destroy')->name('user.destroy');
    });

    // ===============================
    // Kategori Barang Routes
    // ===============================
    Route::controller(KategoriBarangController::class)->group(function () {
        Route::get('/kategori-barang', 'index')->name('kategori_barang.index');
        Route::post('/kategori-barang', 'store')->name('kategori_barang.store');
        Route::put('/kategori-barang/{id}', 'update')->name('kategori_barang.update');
        Route::delete('/kategori-barang/{id}', 'destroy')->name('kategori_barang.destroy');
    });

    Route::get('/admin-jaringan', [JaringanController::class, 'admin_jaringan'])->name('jaringan.admin');
    Route::put('/jaringan/{id}', [JaringanController::class, 'update'])->name('jaringan.update');
    Route::delete('/jaringan/{id}', [JaringanController::class, 'delete'])->name('jaringan.delete');
    Route::post('/jaringan/cetak', [JaringanController::class, 'cetak'])->name('jaringan.cetak');
    Route::get('/jaringan/print/{id}', [JaringanController::class, 'print'])->name('jaringan.print');

    // ruangan
    Route::get('/ruangan', [RuanganController::class, 'index'])->name('ruangan.index');
    Route::post('/ruangan', [RuanganController::class, 'storeRuangan'])->name('ruangan.store');
    Route::put('/ruangan/{id}', [RuanganController::class, 'updateRuangan'])->name('ruangan.update');
    Route::delete('/ruangan/{id}', [RuanganController::class, 'deleteRuangan'])->name('ruangan.delete');
    Route::get('/ruangan/peminjaman', [RuanganController::class, 'peminjamanRuangan'])->name('ruangan.peminjaman');
    Route::delete('/ruangan/peminjaman/{id}', [RuanganController::class, 'deletePeminjamanRuangan'])->name('ruangan.peminjaman.delete');
    Route::put('/ruangan/peminjaman/{id}', [RuanganController::class, 'updatePeminjamanRuangan'])->name('ruangan.peminjaman.update');
});
