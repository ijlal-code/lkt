<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\LtkController;
use App\Http\Controllers\Sp2aController;
use App\Http\Controllers\ContactController;

// Manajemen Kontak (Email)
Route::resource('contacts', ContactController::class)->only(['index', 'store', 'destroy']);

// Manajemen SP2A (Terpisah dari LKT)
Route::resource('sp2a', Sp2aController::class)->only(['index', 'create', 'store']);
Route::post('/sp2a/{id}/approve', [Sp2aController::class, 'approve'])->name('sp2a.approve');

Route::resource('ltk', LtkController::class);
Route::get('ltk/{id}/pdf', [LtkController::class, 'downloadPdf'])->name('ltk.pdf');

