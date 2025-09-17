<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DokumenController;
use App\Http\Controllers\FotoController;
use App\Http\Controllers\VideoController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
| Semua route untuk dokumen, foto, dan video.
| Pastikan model sudah menggunakan SoftDeletes jika ingin restore bekerja.
*/

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