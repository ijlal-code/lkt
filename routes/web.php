<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\LtkController;
use App\Http\Controllers\Sp2aController;

// Group Route SP2A
Route::prefix('sp2a')->name('sp2a.')->group(function () {
    Route::get('/', [Sp2aController::class, 'index'])->name('index'); // Halaman Index
    Route::post('/store', [Sp2aController::class, 'store'])->name('store'); // Simpan Data
    Route::post('/{id}/approve', [Sp2aController::class, 'approve'])->name('approve'); // Approve
});

// Route khusus untuk memanggil Form Create dari LKT
Route::get('/ltk/{ltk_id}/create-sp2a', [Sp2aController::class, 'create'])->name('sp2a.create');

Route::resource('ltk', LtkController::class);
Route::get('ltk/{id}/pdf', [LtkController::class, 'downloadPdf'])->name('ltk.pdf');

