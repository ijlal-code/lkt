<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\LtkController;
use App\Http\Controllers\Sp2aController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PesanController; 
use App\Http\Controllers\AuthController; 
use App\Http\Controllers\HomeController; 

// --- ROUTE AUTENTIKASI ---
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


// --- ROUTE UTAMA (DASHBOARD) ---
// Logika: Jika belum login -> ke Login. Jika sudah -> Tampilkan Dashboard.
Route::get('/', function () {
    if (!Auth::check()) {
        return redirect()->route('login');
    }
    return view('dashboard');
})->name('dashboard');


// --- GROUP ADMIN ---
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::resource('ltk', LtkController::class);
    Route::resource('contacts', ContactController::class);
    Route::resource('users', UserController::class);
});


// --- GROUP INTERNAL / UMUM ---
Route::middleware(['auth'])->group(function () {
    
    // Resource LTK (Staff juga butuh akses ini)
    Route::resource('ltk', LtkController::class)->except(['destroy']); // Sesuaikan jika staff tidak boleh hapus

    // Fitur Pesan
    Route::get('/pesan', [PesanController::class, 'index'])->name('pesan.index');
    Route::get('/pesan/{id}/preview', [PesanController::class, 'previewPage'])->name('pesan.preview');
    Route::get('/pesan/{id}/show', [PesanController::class, 'show'])->name('pesan.show');
    Route::post('/pesan/{id}/approve', [PesanController::class, 'approve'])->name('pesan.approve');

    // Manajemen SP2A
    Route::get('/sp2a/riwayat', [Sp2aController::class, 'history'])->name('sp2a.history');
    Route::get('/sp2a/{id}/download', [Sp2aController::class, 'downloadPdf'])->name('sp2a.download');
    Route::resource('sp2a', Sp2aController::class);
    Route::post('/sp2a/{id}/process', [Sp2aController::class, 'process'])->name('sp2a.process');
    Route::post('/sp2a/{id}/approve', [Sp2aController::class, 'approve'])->name('sp2a.approve');
    Route::post('/sp2a/{id}/koreksi', [Sp2aController::class, 'koreksi'])->name('sp2a.koreksi');
});