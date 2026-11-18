<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\BalanceSheetController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CompanySettingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExpenseReportController;
use App\Http\Controllers\GeneralLedgerController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - CV. GARUDA RAJA PERKASA DUABELAS (7 Golongan Akuntansi)
|--------------------------------------------------------------------------
*/

// Redirect root to dashboard if logged in, otherwise to login
Route::get('/', function () {
    return auth()->check() ? redirect()->route('dashboard') : redirect()->route('login');
});

Route::middleware(['auth', 'role:Admin'])->group(function () {
    // 1. Dashboard (Total Kas, Total Piutang, Total Utang, Laba Bulan Ini, Grafik Pengeluaran)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // 2. Input Transaksi (Form Tambah Pemasukan / Pengeluaran 7 Golongan)
    Route::prefix('transaksi')->name('transaksi.')->group(function () {
        Route::get('/', [TransactionController::class, 'index'])->name('index');
        Route::get('/tambah', [TransactionController::class, 'create'])->name('create');
        Route::post('/simpan', [TransactionController::class, 'store'])->name('store');
        Route::get('/edit/{transaction}', [TransactionController::class, 'edit'])->name('edit');
        Route::put('/update/{transaction}', [TransactionController::class, 'update'])->name('update');
        Route::get('/detail/{transaction}', [TransactionController::class, 'show'])->name('show');
        Route::delete('/hapus/{transaction}', [TransactionController::class, 'destroy'])->name('destroy');
        Route::get('/pemasukan', [TransactionController::class, 'pemasukan'])->name('pemasukan');
        Route::get('/pengeluaran', [TransactionController::class, 'pengeluaran'])->name('pengeluaran');
    });

    // 3. Buku Besar (Semua transaksi per akun, mutasi debit/kredit, saldo berjalan)
    Route::prefix('buku-besar')->name('buku-besar.')->group(function () {
        Route::get('/', [GeneralLedgerController::class, 'index'])->name('index');
        Route::get('/ekspor/pdf', [GeneralLedgerController::class, 'exportPdf'])->name('export.pdf');
        Route::get('/ekspor/excel', [GeneralLedgerController::class, 'exportExcel'])->name('export.excel');
    });

    // 4. Laporan Laba Rugi (Pendapatan - Beban = Laba)
    Route::prefix('laporan')->name('laporan.')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('index');
        Route::get('/laba-rugi', [ReportController::class, 'labaRugi'])->name('laba-rugi');
        Route::get('/ekspor/pdf', [ReportController::class, 'exportPdf'])->name('export.pdf');
        Route::get('/ekspor/excel', [ReportController::class, 'exportExcel'])->name('export.excel');
        Route::get('/laba-rugi/ekspor/pdf', [ReportController::class, 'exportLabaRugiPdf'])->name('laba-rugi.export.pdf');
        Route::get('/laba-rugi/ekspor/excel', [ReportController::class, 'exportLabaRugiExcel'])->name('laba-rugi.export.excel');
    });

    // 5. Neraca (Harta = Utang + Modal)
    Route::prefix('neraca')->name('neraca.')->group(function () {
        Route::get('/', [BalanceSheetController::class, 'index'])->name('index');
        Route::get('/ekspor/pdf', [BalanceSheetController::class, 'exportPdf'])->name('export.pdf');
        Route::get('/ekspor/excel', [BalanceSheetController::class, 'exportExcel'])->name('export.excel');
    });

    // 6. Pengeluaran (Khusus filter semua beban / biaya)
    Route::prefix('pengeluaran')->name('pengeluaran.')->group(function () {
        Route::get('/', [ExpenseReportController::class, 'index'])->name('index');
        Route::get('/ekspor/pdf', [ExpenseReportController::class, 'exportPdf'])->name('export.pdf');
        Route::get('/ekspor/excel', [ExpenseReportController::class, 'exportExcel'])->name('export.excel');
    });

    // 7. Data Akun (Daftar 7 Golongan Akun & CRUD Akun)
    Route::prefix('akun')->name('akun.')->group(function () {
        Route::get('/', [AccountController::class, 'index'])->name('index');
        Route::post('/simpan', [AccountController::class, 'store'])->name('store');
        Route::put('/update/{account}', [AccountController::class, 'update'])->name('update');
        Route::delete('/hapus/{account}', [AccountController::class, 'destroy'])->name('destroy');
    });

    // Kategori Transaksi
    Route::prefix('kategori')->name('kategori.')->group(function () {
        Route::get('/', [CategoryController::class, 'index'])->name('index');
        Route::post('/simpan', [CategoryController::class, 'store'])->name('store');
        Route::put('/update/{category}', [CategoryController::class, 'update'])->name('update');
        Route::delete('/hapus/{category}', [CategoryController::class, 'destroy'])->name('destroy');
    });

    // Profil Perusahaan & Kop Laporan
    Route::prefix('pengaturan')->name('pengaturan.')->group(function () {
        Route::get('/', [CompanySettingController::class, 'index'])->name('index');
        Route::put('/update', [CompanySettingController::class, 'update'])->name('update');
    });

    // Log Aktivitas Audit Trail
    Route::prefix('log-aktivitas')->name('log-aktivitas.')->group(function () {
        Route::get('/', [ActivityLogController::class, 'index'])->name('index');
    });

    // Kelola Pengguna (Khusus Admin tunggal - redirect)
    Route::redirect('/pengguna', '/dashboard');

    // Profil Akun Pengguna
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
