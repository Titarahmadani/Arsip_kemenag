<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Klasifikasi extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_klasifikasi',
    ];
}
