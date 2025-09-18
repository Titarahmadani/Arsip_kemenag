<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    use HasFactory;

    /**
     * Nama tabel (opsional, Laravel otomatis plural: 'fotos').
     */
    protected $table = 'videos';

    /**
     * Kolom yang dapat diisi secara mass-assignment.
     */
    protected $fillable = [
        'nomor_arsip',
        'nama_arsip',
        'kode_klasifikasi',
        'tgl_upload',
        'pencipta_arsip',
        'unit_pengolahan',
        'lokasi_arsip',
        'keterangan',
        'nomor_box',
        'ext',
    ];

    /**
     * Tipe data untuk casting otomatis.
     */
    protected $casts = [
        'tgl_upload' => 'date',
        'nomor_box'  => 'integer',
    ];
}