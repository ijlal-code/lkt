<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\LtkController;
use App\Http\Controllers\Sp2aController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PesanController; 
use App\Http\Controllers\AuthController; 

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
    return redirect()->route('login');
});


// --- GROUP ADMIN (Akses Penuh: Manajemen User, Kontak, LTK) ---
Route::middleware(['auth', 'role:admin'])->group(function () {
    
    // Dashboard & LKT
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::resource('ltk', LtkController::class);
    
    // Manajemen User & Kontak
    Route::resource('contacts', ContactController::class);
    Route::resource('users', UserController::class);

    // NOTE: SP2A dipindahkan dari sini agar Staff & Manager bisa akses
});


// --- GROUP INTERNAL / UMUM (Staff, K3, Auditor, Manager, Admin) ---
Route::middleware(['auth'])->group(function () {
    
    // --- FITUR PESAN (Untuk User/Auditi) ---
    Route::get('/pesan', [PesanController::class, 'index'])->name('pesan.index');
    Route::get('/pesan/{id}/preview', [PesanController::class, 'previewPage'])->name('pesan.preview');
    Route::get('/pesan/{id}/show', [PesanController::class, 'show'])->name('pesan.show');
    Route::post('/pesan/{id}/approve', [PesanController::class, 'approve'])->name('pesan.approve');


    // 1. Route Riwayat Approval (TAMBAHAN BARU) - Taruh SEBELUM Resource
    Route::get('/sp2a/riwayat', [Sp2aController::class, 'history'])->name('sp2a.history');
    // --- MANAJEMEN SP2A (WORKFLOW BARU) ---
    // 1. Resource standar (index, create, store, edit, update, destroy, show)
    Route::resource('sp2a', Sp2aController::class);

    // 2. Route Tambahan untuk Workflow Approval & Koreksi
    // Route untuk melihat detail (bisa pakai default show, tapi kita definisikan eksplisit jika butuh custom URL)
    // Route::get('/sp2a/{id}/show', [Sp2aController::class, 'show'])->name('sp2a.show'); // (Opsional karena sudah ada di resource)

    // Route Action Approval (SM -> SMQA -> GM)
    Route::post('/sp2a/{id}/approve', [Sp2aController::class, 'approve'])->name('sp2a.approve');
    
    // Route Action Koreksi (Kembalikan ke Staff)
    Route::post('/sp2a/{id}/koreksi', [Sp2aController::class, 'koreksi'])->name('sp2a.koreksi');
    
    // Route Proses Penomoran (Opsional, jika masih dipakai manual oleh Admin, tapi sekarang otomatis di GM)
    Route::post('/sp2a/{id}/process', [Sp2aController::class, 'process'])->name('sp2a.process');
});