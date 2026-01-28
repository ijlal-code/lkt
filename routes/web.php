<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\LtkController;
use App\Http\Controllers\Sp2aController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PesanController;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    if (Auth::check()) {
        if (Auth::user()->role == 'admin') {
            return redirect()->route('ltk.index');
        } else {
            return redirect()->route('pesan.index');
        }
    }
    return view('auth.login'); // Pastikan Anda punya view login
});

// Group Admin
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::resource('users', UserController::class);
    // ... route admin lainnya ...
});

// Group User (K3, Auditor, dll) - Halaman Pesan
Route::middleware(['auth'])->group(function () {
    Route::get('/pesan', [PesanController::class, 'index'])->name('pesan.index');
    Route::get('/pesan/{id}', [PesanController::class, 'show'])->name('pesan.show');
    Route::post('/pesan/{id}/approve', [PesanController::class, 'approve'])->name('pesan.approve');
});

// Helper untuk cek role (Tambahkan ini di app/Http/Middleware/CheckRole.php jika belum ada, 
// atau simpelnya pakai Gate/Logic di Controller)

// Route Manajemen User
Route::resource('users', UserController::class);
// Manajemen Kontak (Email)
Route::resource('contacts', ContactController::class)->only(['index', 'store', 'destroy']);

// Manajemen SP2A (Terpisah dari LKT)
Route::resource('sp2a', Sp2aController::class)->only(['index', 'create', 'store']);
Route::post('/sp2a/{id}/process', [Sp2aController::class, 'process'])->name('sp2a.process');

Route::resource('ltk', LtkController::class);
Route::get('ltk/{id}/pdf', [LtkController::class, 'downloadPdf'])->name('ltk.pdf');

