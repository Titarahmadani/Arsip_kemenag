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
use App\Http\Controllers\DokumenController;
use App\Http\Controllers\FotoController;
use App\Http\Controllers\VideoController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Dashboard
Route::get('/grafik', [DashboardController::class, 'grafik']);

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

/* ===================== DOKUMEN ===================== */
Route::get('/dokumen', [DokumenController::class, 'index'])->name('dokumen.viewdokumen');
Route::get('/dokumen/create', [DokumenController::class, 'create'])->name('dokumen.create');
Route::post('/dokumen', [DokumenController::class, 'store'])->name('dokumen.store');
Route::get('/dokumen/{id}/edit', [DokumenController::class, 'edit'])->name('dokumen.edit');
Route::put('/dokumen/{id}', [DokumenController::class, 'update'])->name('dokumen.update');
Route::delete('/dokumen/{id}', [DokumenController::class, 'destroy'])->name('dokumen.destroy');
Route::post('/dokumen/{id}/restore', [DokumenController::class, 'restore'])->name('dokumen.restore');
Route::get('/dokumen-restore', function () {
    $trashed = \App\Models\Dokumen::onlyTrashed()->paginate(10);
    return view('dokumen.viewrestore', compact('trashed'));
})->name('dokumen.viewrestore');

/* ===================== FOTO ===================== */
Route::get('/foto', [FotoController::class, 'index'])->name('foto.viewfoto');
Route::get('/foto/create', [FotoController::class, 'create'])->name('foto.create');
Route::post('/foto', [FotoController::class, 'store'])->name('foto.store');
Route::get('/foto/{id}/edit', [FotoController::class, 'edit'])->name('foto.edit');
Route::put('/foto/{id}', [FotoController::class, 'update'])->name('foto.update');
Route::delete('/foto/{id}', [FotoController::class, 'destroy'])->name('foto.destroy');
Route::post('/foto/{id}/restore', [FotoController::class, 'restore'])->name('foto.restore');
Route::get('/foto-restore', function () {
    $trashed = \App\Models\Foto::onlyTrashed()->paginate(10);
    return view('foto.viewrestore', compact('trashed'));
})->name('foto.viewrestore');

/* ===================== VIDEO ===================== */
Route::get('/video', [VideoController::class, 'index'])->name('video.viewvideo');
Route::get('/video/create', [VideoController::class, 'create'])->name('video.create');
Route::post('/video', [VideoController::class, 'store'])->name('video.store');
Route::get('/video/{id}/edit', [VideoController::class, 'edit'])->name('video.edit');
Route::put('/video/{id}', [VideoController::class, 'update'])->name('video.update');
Route::delete('/video/{id}', [VideoController::class, 'destroy'])->name('video.destroy');
Route::post('/video/{id}/restore', [VideoController::class, 'restore'])->name('video.restore');
Route::get('/video-restore', function () {
    $trashed = \App\Models\Video::onlyTrashed()->paginate(10);
    return view('video.viewrestore', compact('trashed'));
})->name('video.viewrestore');
