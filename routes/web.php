<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\AuthController; // Controller baru untuk Login/Register

// ==========================================
// 1. JALUR AKSES SEBELUM LOGIN (GUEST)
// ==========================================
Route::middleware('guest')->group(function () {
    Route::get('/', function () { return redirect('/login'); });
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister']);
    Route::post('/register', [AuthController::class, 'register']);
});

// ==========================================
// 2. JALUR AKSES SETELAH LOGIN (AUTH)
// ==========================================
Route::middleware('auth')->group(function () {
    // Keluar Aplikasi
    Route::post('/logout', [AuthController::class, 'logout']);

    // Halaman 2: Dashboard Utama (Daftar Proyek)
    Route::get('/dashboard', [ProjectController::class, 'dashboard']);
    Route::post('/project/create', [ProjectController::class, 'createProject']);

    // Halaman 3: Timeline Progress Proyek (Detail)
    Route::get('/project/{id}', [ProjectController::class, 'showProject']);
    Route::post('/project/{id}/upload-progress', [ProjectController::class, 'uploadProgress']);
    Route::post('/project/{id}/add-member', [ProjectController::class, 'addMember']);

    // Halaman 4: Schedule (Manajemen Jadwal Luang)
    Route::get('/project/{id}/schedule', [ProjectController::class, 'showSchedule']);
    Route::post('/project/{id}/add-schedule', [ProjectController::class, 'addSchedule']);
    Route::get('/project/{id}/match-schedule', [ProjectController::class, 'matchSchedule']);
});