<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TugasAkhirController;
use App\Http\Controllers\LogbookController;
use App\Http\Controllers\SidangController;
use App\Http\Controllers\PengumumanController;
use App\Http\Controllers\AdminController;

use App\Http\Controllers\HomeController;

// Rute default menuju dashboard umum
Route::get('/', [HomeController::class, 'index'])->name('home');

// Guest Routes (Hanya untuk pengguna yang belum login)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// Authenticated Routes (Harus login)
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Rute cetak (Shared access dengan policy otorisasi internal di Controller)
    Route::get('/tugas-akhir/{id}/print-logbook', [LogbookController::class, 'print'])->name('ta.print_logbook');
    Route::get('/sidang/{id}/print-berita-acara', [SidangController::class, 'printBeritaAcara'])->name('sidang.print_berita_acara');
    
    // Rute hapus pengumuman (Shared Admin & Dosen pembuat)
    Route::delete('/pengumuman/{id}', [PengumumanController::class, 'destroy'])->name('pengumuman.destroy');

    // Rute Mahasiswa
    Route::middleware('role:mahasiswa')->group(function () {
        Route::get('/mahasiswa/tugas-akhir/pengajuan', [TugasAkhirController::class, 'create'])->name('mahasiswa.ta.create');
        Route::post('/mahasiswa/tugas-akhir', [TugasAkhirController::class, 'store'])->name('mahasiswa.ta.store');
        Route::put('/mahasiswa/tugas-akhir/{id}', [TugasAkhirController::class, 'update'])->name('mahasiswa.ta.update');
        Route::post('/mahasiswa/tugas-akhir/{id}/upload-report', [TugasAkhirController::class, 'uploadReport'])->name('mahasiswa.ta.upload');
        Route::post('/mahasiswa/tugas-akhir/{id}/apply-sidang', [SidangController::class, 'apply'])->name('mahasiswa.ta.apply_sidang');

        Route::get('/mahasiswa/logbook', [DashboardController::class, 'logbookIndex'])->name('mahasiswa.logbook.index');
        Route::post('/mahasiswa/logbook', [LogbookController::class, 'store'])->name('mahasiswa.logbook.store');
        Route::put('/mahasiswa/logbook/{id}', [LogbookController::class, 'update'])->name('mahasiswa.logbook.update');
        Route::delete('/mahasiswa/logbook/{id}', [LogbookController::class, 'destroy'])->name('mahasiswa.logbook.destroy');

        Route::get('/mahasiswa/laporan', [DashboardController::class, 'laporanIndex'])->name('mahasiswa.laporan.index');
        Route::get('/mahasiswa/riwayat', [DashboardController::class, 'riwayatIndex'])->name('mahasiswa.riwayat.index');
    });

    // Rute Dosen
    Route::middleware('role:dosen')->group(function () {
        Route::post('/dosen/logbook/{id}/validate', [LogbookController::class, 'validateLogbook'])->name('dosen.logbook.validate');
        Route::post('/dosen/sidang/{id}/score', [SidangController::class, 'score'])->name('dosen.sidang.score');
        
        // Dosen juga bisa approve judul TA mahasiswa asuhannya
        Route::post('/dosen/tugas-akhir/{id}/approve', [TugasAkhirController::class, 'approve'])->name('dosen.ta.approve');
    });

    // Rute Admin
    Route::middleware('role:admin')->group(function () {
        // Management Mahasiswa
        Route::get('/admin/mahasiswa', [AdminController::class, 'studentIndex'])->name('admin.mahasiswa.index');
        Route::post('/admin/mahasiswa', [AdminController::class, 'studentStore'])->name('admin.mahasiswa.store');
        Route::put('/admin/mahasiswa/{id}', [AdminController::class, 'studentUpdate'])->name('admin.mahasiswa.update');
        Route::delete('/admin/mahasiswa/{id}', [AdminController::class, 'studentDestroy'])->name('admin.mahasiswa.destroy');

        // Management Dosen
        Route::get('/admin/dosen', [AdminController::class, 'dosenIndex'])->name('admin.dosen.index');
        Route::post('/admin/dosen', [AdminController::class, 'dosenStore'])->name('admin.dosen.store');
        Route::put('/admin/dosen/{id}', [AdminController::class, 'dosenUpdate'])->name('admin.dosen.update');
        Route::delete('/admin/dosen/{id}', [AdminController::class, 'dosenDestroy'])->name('admin.dosen.destroy');

        // Plotting Sidang
        Route::post('/admin/sidang/schedule', [SidangController::class, 'schedule'])->name('admin.sidang.schedule');

        // Pengumuman
        Route::post('/admin/pengumuman', [PengumumanController::class, 'store'])->name('admin.pengumuman.store');

        // Ekspor Laporan Excel/CSV
        Route::get('/admin/export-csv', [AdminController::class, 'exportCSV'])->name('admin.export_csv');

        // Admin juga bisa approve/tolak judul TA mahasiswa
        Route::post('/admin/tugas-akhir/{id}/approve', [TugasAkhirController::class, 'approve'])->name('admin.ta.approve');
    });
});
