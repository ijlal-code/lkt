<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\LtkController;

Route::resource('ltk', LtkController::class);
Route::get('ltk/{id}/pdf', [LtkController::class, 'downloadPdf'])->name('ltk.pdf');

