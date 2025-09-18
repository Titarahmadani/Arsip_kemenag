<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Aktivitas extends Model
{
    use HasFactory;

    protected $fillable = [
        'tanggal',
        'user_id',
        'aksi',
    ];
}
