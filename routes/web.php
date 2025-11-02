<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MahasiswaController;



Route::get('/mahasiswa/export-excel', [MahasiswaController::class, 'exportExcel'])->name('mahasiswa.exportExcel');
Route::get('/mahasiswa/cetak-pdf', [MahasiswaController::class, 'cetakPDF'])->name('mahasiswa.cetakPDF');
Route::get('/mahasiswa', [MahasiswaController::class, 'index'])->name('mahasiswa.index');

Route::resource('mahasiswa', MahasiswaController::class);