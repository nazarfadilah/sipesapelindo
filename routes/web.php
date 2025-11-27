<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardStatsController;
use App\Http\Controllers\User\PetugasController;
use App\Http\Controllers\User\SuperAdminController;
use App\Http\Controllers\User\AdminController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\SuperAdmin\LaporanController as SuperAdminLaporanController;

// Public routes
Route::get('/', function () {
    return view('welcome');
});

// Unauthorized route
Route::get('/unauthorized', function () {
    return view('errors.unauthorized');
})->name('unauthorized');

// Authentication routes
Route::get('/login', [AuthController::class, 'FormLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// SuperAdmin routes (Role 1)
Route::prefix('superadmin')->name('superadmin.')->middleware('superadmin')->group(function () {
    // Dashboard
    Route::get('/dashboard', [SuperAdminController::class, 'dashboard'])->name('dashboard');

    // Master Data routes
    Route::prefix('master')->name('master.')->group(function () {
        Route::get('/users', [SuperAdminController::class, 'masterUsers'])->name('users');
        Route::get('/sampah-terkelola', [SuperAdminController::class, 'masterSampahTerkelola'])->name('sampah-terkelola');
        Route::get('/sampah-diserahkan', [SuperAdminController::class, 'masterSampahDiserahkan'])->name('sampah-diserahkan');
        Route::get('/lokasi-asal', [SuperAdminController::class, 'masterLokasiAsal'])->name('lokasi-asal');
        Route::get('/jenis-sampah', [SuperAdminController::class, 'masterJenisSampah'])->name('jenis-sampah');
        Route::get('/tujuan-sampah', [SuperAdminController::class, 'masterTujuanSampah'])->name('tujuan-sampah');
        Route::get('/dokumen', [SuperAdminController::class, 'masterDokumen'])->name('dokumen');
    });

    // Laporan routes
    Route::prefix('laporan')->name('laporan.')->group(function () {
        Route::get('/', [SuperAdminLaporanController::class, 'index'])->name('index');
        Route::get('/export', [SuperAdminLaporanController::class, 'export'])->name('export');

        // Bagian yang tadi error (conflict) sudah diperbaiki di sini:
        Route::get('/harian', [SuperAdminController::class, 'laporanHarian'])->name('harian');
        Route::get('/mingguan', [SuperAdminController::class, 'laporanMingguan'])->name('mingguan');
        Route::get('/bulanan', [SuperAdminController::class, 'laporanBulanan'])->name('bulanan');
        Route::get('/tahunan', [SuperAdminController::class, 'laporanTahunan'])->name('tahunan');
    });
});

// Admin routes (Role 2)
Route::prefix('admin')->name('admin.')->middleware('admin')->group(function () {
    // Dashboard
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // Kelola Petugas
    Route::prefix('kelola')->name('kelola.')->group(function () {
        Route::get('/petugas', [AdminController::class, 'kelolaPetugas'])->name('petugas');
    });

    // Laporan routes
    Route::prefix('laporan')->name('laporan.')->group(function () {
        Route::get('/', [LaporanController::class, 'index'])->name('index');
        Route::get('/export', [LaporanController::class, 'export'])->name('export');
    });

    Route::get('/kelola-petugas', [AdminController::class, 'kelolaPetugas'])->name('kelola-petugas');
    Route::get('/tambah-petugas', [AdminController::class, 'tambahPetugas'])->name('tambah-petugas');
    Route::post('/store-petugas', [AdminController::class, 'storePetugas'])->name('store-petugas');
    Route::get('/edit-petugas/{id}', [AdminController::class, 'editPetugas'])->name('edit-petugas');
    Route::put('/update-petugas/{id}', [AdminController::class, 'updatePetugas'])->name('update-petugas');
    Route::delete('/delete-petugas/{id}', [AdminController::class, 'deletePetugas'])->name('delete-petugas');

    // Admin tidak memiliki akses input sampah
    
    // Tabulasi Data (Admin dapat melihat dan edit)
    Route::prefix('data')->name('data.')->group(function() {
        // Sampah Terkelola
        Route::get('/sampah-terkelola', [AdminController::class, 'dataSampahTerkelola'])->name('sampah-terkelola');
        Route::get('/sampah-terkelola/{id}/edit', [AdminController::class, 'editSampahTerkelola'])->name('sampah-terkelola.edit');
        Route::put('/sampah-terkelola/{id}/update', [AdminController::class, 'updateSampahTerkelola'])->name('sampah-terkelola.update');
        
        // Sampah Diserahkan
        Route::get('/sampah-diserahkan', [AdminController::class, 'dataSampahDiserahkan'])->name('sampah-diserahkan');
        Route::get('/sampah-diserahkan/{id}/edit', [AdminController::class, 'editSampahDiserahkan'])->name('sampah-diserahkan.edit');
        Route::put('/sampah-diserahkan/{id}/update', [AdminController::class, 'updateSampahDiserahkan'])->name('sampah-diserahkan.update');dokumen', [AdminController::class, 'dataDokumen'])->name('dokumen');
    });

    // Kelola Dokumen (Admin dapat CRUD dokumen)
    Route::prefix('dokumen')->name('dokumen.')->group(function() {
        Route::get('/', [AdminController::class, 'dokumenIndex'])->name('index');
        Route::get('/create', [AdminController::class, 'dokumenCreate'])->name('create');
        Route::post('/store', [AdminController::class, 'dokumenStore'])->name('store');
        Route::get('/edit/{id}', [AdminController::class, 'dokumenEdit'])->name('edit');
        Route::put('/update/{id}', [AdminController::class, 'dokumenUpdate'])->name('update');
        Route::delete('/destroy/{id}', [AdminController::class, 'dokumenDestroy'])->name('destroy');
    });

    // Master Data
    Route::prefix('master')->name('master.')->group(function() {
        Route::get('/lokasi-asal', [AdminController::class, 'masterLokasiAsal'])->name('lokasi-asal');
        Route::get('/jenis-sampah', [AdminController::class, 'masterJenisSampah'])->name('jenis-sampah');
        Route::get('/tujuan-sampah', [AdminController::class, 'masterTujuanSampah'])->name('tujuan-sampah');

        // CRUD untuk lokasi asal
        Route::post('/lokasi-asal/store', [AdminController::class, 'storeLokasiAsal'])->name('lokasi-asal.store');
        Route::put('/lokasi-asal/{id}/update', [AdminController::class, 'updateLokasiAsal'])->name('lokasi-asal.update');
        Route::delete('/lokasi-asal/{id}/delete', [AdminController::class, 'deleteLokasiAsal'])->name('lokasi-asal.delete');

        // CRUD untuk jenis sampah
        Route::post('/jenis-sampah/store', [AdminController::class, 'storeJenisSampah'])->name('jenis-sampah.store');
        Route::put('/jenis-sampah/{id}/update', [AdminController::class, 'updateJenisSampah'])->name('jenis-sampah.update');
        Route::delete('/jenis-sampah/{id}/delete', [AdminController::class, 'deleteJenisSampah'])->name('jenis-sampah.delete');

        // CRUD untuk tujuan sampah
        Route::post('/tujuan-sampah/store', [AdminController::class, 'storeTujuanSampah'])->name('tujuan-sampah.store');
        Route::put('/tujuan-sampah/{id}/update', [AdminController::class, 'updateTujuanSampah'])->name('tujuan-sampah.update');
        Route::delete('/tujuan-sampah/{id}/delete', [AdminController::class, 'deleteTujuanSampah'])->name('tujuan-sampah.delete');
    });
});

// Petugas routes (Role 3)
Route::prefix('petugas')->name('petugas.')->middleware('petugas')->group(function () {
    Route::get('/dashboard', [PetugasController::class, 'dashboard'])->name('dashboard');
    Route::get('/dashboard-stats', [DashboardStatsController::class, 'getStats'])->name('dashboard-stats');

    // Halaman data sampah
    Route::get('/sampah-terkelola', [PetugasController::class, 'sampahTerkelola'])->name('sampah-terkelola');
    Route::get('/sampah-diserahkan', [PetugasController::class, 'sampahDiserahkan'])->name('sampah-diserahkan');

    // Halaman input data
    Route::get('/input-sampah-terkelola', [PetugasController::class, 'inputSampahTerkelola'])->name('input-sampah-terkelola');
    Route::post('/store-sampah-terkelola', [PetugasController::class, 'storeSampahTerkelola'])->name('store-sampah-terkelola');
    Route::get('/input-sampah-diserahkan', [PetugasController::class, 'inputSampahDiserahkan'])->name('input-sampah-diserahkan');
    Route::post('/store-sampah-diserahkan', [PetugasController::class, 'storeSampahDiserahkan'])->name('store-sampah-diserahkan');
});
