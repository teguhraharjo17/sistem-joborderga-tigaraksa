<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\FormLaporan\FormLaporanController;
use App\Http\Controllers\ListLaporanKerusakan\ListLaporanKerusakanController;
use App\Http\Controllers\LaporanKerja\LaporanKerjaController;
use \App\Http\Controllers\ProgressKerja\ProgressKerjaController;

// ===========================
// Public Route
// ===========================
Route::get('/error', fn () => abort(500))->name('error');

// ===========================
// Protected Routes (auth + verified)
// ===========================
Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard (Grouped with Prefix + Name)
    Route::prefix('dashboard')->name('dashboard.')->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('index');
    });

    // Redirect root to dashboard.index
    Route::get('/', fn () => redirect()->route('dashboard.index'));

    // ===========================
    // Admin Routes (Super Admin & Admin only)
    // ===========================
    Route::prefix('admin')->name('admin.')->middleware('can:isAdminOrSuperAdmin')->group(function () {
        Route::get('/register', [RegisteredUserController::class, 'create'])->name('make-account');
        Route::post('/register', [RegisteredUserController::class, 'store'])->name('register');
    });

    Route::middleware(['auth', 'verified'])->prefix('form-laporan')->name('formlaporan.')->group(function () {
        Route::get('/', [FormLaporanController::class, 'index'])->name('index');
        Route::post('/', [FormLaporanController::class, 'store'])->name('store');
        Route::get('/{id}', [FormLaporanController::class, 'show'])->name('show');
        Route::post('/{id}/ttd/{role}', [FormLaporanController::class, 'ttd'])->name('ttd');
    });

    Route::prefix('list-laporan-kerusakan')->name('listlaporankerusakan.')->group(function () {
        Route::get('/', [ListLaporanKerusakanController::class, 'index'])->name('index');
        Route::get('/edit/{id}', [ListLaporanKerusakanController::class, 'edit'])->name('edit');
        Route::post('/update-ttd/{id}', [ListLaporanKerusakanController::class, 'updateTtd'])->name('update-ttd');
        Route::get('/data', [ListLaporanKerusakanController::class, 'data'])->name('data');
    });

    Route::prefix('laporan-kerja')->name('laporankerja.')->group(function () {
        Route::get('/', [LaporanKerjaController::class, 'index'])->name('index');
        Route::get('/data', [LaporanKerjaController::class, 'data'])->name('data');
        Route::post('/store', [LaporanKerjaController::class, 'store'])->name('store');
        Route::get('/export', [LaporanKerjaController::class, 'export'])->name('export');
    });

    Route::prefix('progress-kerja')->name('progresskerja.')->group(function () {
        Route::get('/', [ProgressKerjaController::class, 'index'])->name('index');
        Route::get('/data', [ProgressKerjaController::class, 'data'])->name('data');
    });
});

// ===========================
// Include Auth Routes
// ===========================
require __DIR__ . '/auth.php';
