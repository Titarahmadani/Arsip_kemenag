<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserlistController;
use App\Http\Controllers\AdminlistController;
use App\Http\Controllers\RuangController;
use App\Http\Controllers\KlasifikasiController;
use App\Http\Controllers\PenciptaController;
use App\Http\Controllers\PengolahController;
use App\Http\Controllers\LokasiController;

Route::get('/grafik', [App\Http\Controllers\DashboardController::class, 'grafik']);

// Userlist
Route::get('/userlist', [UserlistController::class, 'index'])->name('userlist.index');
Route::get('/userlist/create', [UserlistController::class, 'create'])->name('userlist.create');
Route::post('/userlist', [UserlistController::class, 'store'])->name('userlist.store');
Route::get('/userlist/{id}/edit', [UserlistController::class, 'edit'])->name('userlist.edit');
Route::put('/userlist/{id}', [UserlistController::class, 'update'])->name('userlist.update');
Route::delete('/userlist/{id}', [UserlistController::class, 'destroy'])->name('userlist.destroy');

// Adminlist
Route::get('/adminlist', [AdminlistController::class, 'index'])->name('adminlist.index');
Route::get('/adminlist/create', [AdminlistController::class, 'create'])->name('adminlist.create');
Route::post('/adminlist', [AdminlistController::class, 'store'])->name('adminlist.store');
Route::get('/adminlist/{id}/edit', [AdminlistController::class, 'edit'])->name('adminlist.edit');
Route::put('/adminlist/{id}', [AdminlistController::class, 'update'])->name('adminlist.update');
Route::delete('/adminlist/{id}', [AdminlistController::class, 'destroy'])->name('adminlist.destroy');

// Ruang
Route::get('/ruang', [RuangController::class, 'index'])->name('ruang.index');
Route::get('/ruang/create', [RuangController::class, 'create'])->name('ruang.create');
Route::post('/ruang', [RuangController::class, 'store'])->name('ruang.store');
Route::get('/ruang/{id}/edit', [RuangController::class, 'edit'])->name('ruang.edit');
Route::put('/ruang/{id}', [RuangController::class, 'update'])->name('ruang.update');
Route::delete('/ruang/{id}', [RuangController::class, 'destroy'])->name('ruang.destroy');

// Kode Klasifikasi
Route::get('/kode_klasifikasi', [KlasifikasiController::class, 'index'])->name('kode_klasifikasi.index');
Route::get('/kode_klasifikasi/create', [KlasifikasiController::class, 'create'])->name('kode_klasifikasi.create');
Route::post('/kode_klasifikasi', [KlasifikasiController::class, 'store'])->name('kode_klasifikasi.store');
Route::get('/kode_klasifikasi/{id}/edit', [KlasifikasiController::class, 'edit'])->name('kode_klasifikasi.edit');
Route::put('/kode_klasifikasi/{id}', [KlasifikasiController::class, 'update'])->name('kode_klasifikasi.update');
Route::delete('/kode_klasifikasi/{id}', [KlasifikasiController::class, 'destroy'])->name('kode_klasifikasi.destroy');

// Pencipta Arsip
Route::get('/pencipta_arsip', [PenciptaController::class, 'index'])->name('pencipta_arsip.index');
Route::get('/pencipta_arsip/create', [PenciptaController::class, 'create'])->name('pencipta_arsip.create');
Route::post('/pencipta_arsip', [PenciptaController::class, 'store'])->name('pencipta_arsip.store');
Route::get('/pencipta_arsip/{id}/edit', [PenciptaController::class, 'edit'])->name('pencipta_arsip.edit');
Route::put('/pencipta_arsip/{id}', [PenciptaController::class, 'update'])->name('pencipta_arsip.update');
Route::delete('/pencipta_arsip/{id}', [PenciptaController::class, 'destroy'])->name('pencipta_arsip.destroy');

// Pengolah Arsip
Route::get('/pengolah_arsip', [PengolahController::class, 'index'])->name('pengolah_arsip.index');
Route::get('/pengolah_arsip/create', [PengolahController::class, 'create'])->name('pengolah_arsip.create');
Route::post('/pengolah_arsip', [PengolahController::class, 'store'])->name('pengolah_arsip.store');
Route::get('/pengolah_arsip/{id}/edit', [PengolahController::class, 'edit'])->name('pengolah_arsip.edit');
Route::put('/pengolah_arsip/{id}', [PengolahController::class, 'update'])->name('pengolah_arsip.update');
Route::delete('/pengolah_arsip/{id}', [PengolahController::class, 'destroy'])->name('pengolah_arsip.destroy');

// Pengolah Arsip
Route::get('/pengolah_arsip', [PengolahController::class, 'index'])->name('pengolah_delete.index');
Route::get('/pengolah_arsip/create', [PengolahController::class, 'create'])->name('pengolah_arsip.create');
Route::post('/pengolah_arsip', [PengolahController::class, 'store'])->name('pengolah_arsip.store');
Route::get('/pengolah_arsip/{id}/edit', [PengolahController::class, 'edit'])->name('pengolah_arsip.edit');
Route::put('/pengolah_arsip/{id}', [PengolahController::class, 'update'])->name('pengolah_arsip.update');
Route::delete('/pengolah_arsip/{id}', [PengolahController::class, 'destroy'])->name('pengolah_arsip.destroy');
