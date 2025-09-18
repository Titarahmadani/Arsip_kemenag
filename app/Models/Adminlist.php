<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Adminlist extends Model
{
   use HasFactory;

    protected $fillable = [
        'user_id',
        'email',
        'role_id',
        'division_id',
        'division',
        'profile_picture',
        'status',
        'terakhir_login',
        'terakhir_logout',
    ];
}
