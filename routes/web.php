<?php

use Illuminate\Support\Facades\Route;
use App\Services\TopsisService;
use App\Services\BordaService;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EvaluationController;
use App\Http\Controllers\ConsensusController; // <--- TAMBAHKAN INI
use App\Http\Controllers\AdminController;

// ====================================================
// BAGIAN 1: AUTHENTICATION & UTAMA
// ====================================================

Route::get('/', [AuthController::class, 'showLoginForm'])->name('login');
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ====================================================
// BAGIAN 2: DASHBOARD (Perlu Login)
// ====================================================
Route::middleware(['auth'])->group(function () {
    
    // Route untuk Area Manager
    Route::get('/dashboard/area', function () {
        return view('dashboard.area'); // Nanti kita buat filenya
    })->name('dashboard.area');

    // Route untuk Kepala Toko
    Route::get('/dashboard/store', function () {
        return view('dashboard.store'); // Nanti kita buat filenya
    })->name('dashboard.store');

    // Route untuk HR
    Route::get('/dashboard/hr', function () {
        return view('dashboard.hr'); // Nanti kita buat filenya
    })->name('dashboard.hr');

    // --- MODUL PENILAIAN ---
    Route::get('/evaluation', [\App\Http\Controllers\EvaluationController::class, 'index'])->name('evaluation.index');
    Route::post('/evaluation', [\App\Http\Controllers\EvaluationController::class, 'store'])->name('evaluation.store');

    // --- MODUL KONSENSUS (BORDA) ---
    Route::get('/consensus', [\App\Http\Controllers\ConsensusController::class, 'index'])->name('consensus.index');
    Route::post('/consensus/generate', [\App\Http\Controllers\ConsensusController::class, 'generate'])->name('consensus.generate');

    // --- MODUL ADMIN (MANAGEMENT) ---
    // Dashboard Utama
    Route::get('/dashboard/admin', [\App\Http\Controllers\AdminController::class, 'index'])->name('dashboard.admin');
    
    // Aksi Admin (Ganti Password)
    Route::put('/admin/user/{id}/change-password', [\App\Http\Controllers\AdminController::class, 'changePassword'])->name('admin.changePassword');
    
    // Kandidat CRUD
    Route::post('/admin/candidate', [\App\Http\Controllers\AdminController::class, 'storeCandidate'])->name('admin.candidate.store');
    Route::put('/admin/candidate/{id}', [\App\Http\Controllers\AdminController::class, 'updateCandidate'])->name('admin.candidate.update');
    Route::delete('/admin/candidate/{id}', [\App\Http\Controllers\AdminController::class, 'deleteCandidate'])->name('admin.candidate.delete');

    // Kriteria CRUD
    Route::post('/admin/criteria', [\App\Http\Controllers\AdminController::class, 'storeCriteria'])->name('admin.criteria.store');
    Route::put('/admin/criteria/{id}', [\App\Http\Controllers\AdminController::class, 'updateCriteria'])->name('admin.criteria.update');
    Route::delete('/admin/criteria/{id}', [\App\Http\Controllers\AdminController::class, 'deleteCriteria'])->name('admin.criteria.delete');

});

// ====================================================
// BAGIAN 3: TESTING LOGIC (JANGAN DIHAPUS DULU)
// ====================================================
// Biarkan route ini aktif untuk debugging jika nanti ada masalah hitungan
// Route::get('/test-topsis', function (TopsisService $topsisService) {
//     // ... (Kode test TOPSIS lama biarkan disini jika butuh cek json lagi) ...
//     return "Logic Test Mode (Silakan cek code di routes/web.php jika ingin mengaktifkan lagi)";
// });

// Route::get('/test-borda', function (TopsisService $ts, BordaService $bs) {
//      // ... (Kode test BORDA lama biarkan disini) ...
//      return "Logic Test Mode (Silakan cek code di routes/web.php jika ingin mengaktifkan lagi)";
// });