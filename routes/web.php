<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\LtkController;
use App\Http\Controllers\Sp2aController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PesanController; // Controller Baru
use App\Http\Controllers\AuthController; // <--- Import AuthController

// --- ROUTE AUTENTIKASI MANUAL ---
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


Route::get('/', function () {
    // Jika sudah login, cek role untuk redirect
    if (Auth::check()) {
        if (Auth::user()->role == 'admin') {
            return redirect()->route('ltk.index');
        } else {
            return redirect()->route('pesan.index');
        }
    }
    // Jika belum login, ke halaman welcome/login
    return view('welcome'); 
});



// --- GROUP ADMIN (Akses Penuh) ---
Route::middleware(['auth', 'role:admin'])->group(function () {
    
    // Dashboard & LKT
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::resource('ltk', LtkController::class);
    
    // Manajemen User & Kontak
    Route::resource('contacts', ContactController::class);
    Route::resource('users', UserController::class);

    // Manajemen SP2A (Admin hanya Buat & Proses Awal)
    Route::resource('sp2a', Sp2aController::class);
    Route::post('/sp2a/{id}/process', [Sp2aController::class, 'process'])->name('sp2a.process');
});

// --- GROUP USER (K3, Auditor, Staff, dll) ---
Route::middleware(['auth'])->group(function () {
    Route::get('/pesan', [PesanController::class, 'index'])->name('pesan.index');
    
    // Halaman yang ada bingkainya
    Route::get('/pesan/{id}/preview', [PesanController::class, 'previewPage'])->name('pesan.preview');
    
    // Endpoint khusus untuk konten PDF-nya
    Route::get('/pesan/{id}/show', [PesanController::class, 'show'])->name('pesan.show');
    
    // Proses Approve
    Route::post('/pesan/{id}/approve', [PesanController::class, 'approve'])->name('pesan.approve');
});