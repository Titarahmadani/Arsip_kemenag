<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Dokumen extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * Nama tabel (opsional, Laravel otomatis plural: 'fotos').
     */
    protected $table = 'dokumen';

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