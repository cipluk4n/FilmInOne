<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProjectController;
use App\Models\Project;
use App\Models\User;

// Menangani jika user mengakses halaman utama tanpa ujung
Route::get('/', function () {
    // Otomatis pindahkan user ke halaman proyek ID 1
    return redirect('/project/1');
});

// Halaman utama untuk melihat detail proyek FilmInOne secara langsung
Route::get('/project/{id}', function ($id) {
    
    // PAKSA LOGIN (Hapus/Komentari fungsi IF-nya)
    // Jika mau jadi Andi, ketik 1. Jika mau jadi Budi, ketik 2.
    auth()->loginUsingId(1  ); 

    $project = Project::with(['members', 'progresses'])->findOrFail($id);
    $all_users = User::all();

    return view('project-detail', compact('project', 'all_users'));
});

// Route untuk memproses form tambah anggota
Route::post('/project/{id}/add-member', [ProjectController::class, 'addMember']);

// Route untuk memproses form upload progress proyek / berkas editing
Route::post('/project/{id}/upload-progress', [ProjectController::class, 'uploadProgress']);

// Jalur untuk memproses simpan jadwal luang
Route::post('/project/{id}/add-schedule', [ProjectController::class, 'addSchedule']);

// Jalur untuk menghitung kecocokan jadwal syuting
Route::get('/project/{id}/match-schedule', [ProjectController::class, 'matchSchedule']);

Route::get('/test-notif', function() {
    $user = \App\Models\User::find(1); // Andi
    $progress = \App\Models\ProjectProgress::first(); // Ambil progress apa saja yang ada
    
    if(!$progress) {
        return "Gagal tes: Anda harus upload minimal 1 file dulu di aplikasi agar ada data progress.";
    }

    $user->notify(new \App\Notifications\ProgressUploadedNotification($progress));
    
    return "Notifikasi berhasil ditembak langsung ke database Andi! Silakan cek halaman project.";
});