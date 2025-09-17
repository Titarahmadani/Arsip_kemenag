<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $table = 'roles';

    /**
     * Kolom yang bisa diisi secara mass-assignment.
     */
    protected $fillable = [
        'name', // misalnya: 'admin', 'user'
    ];

    /**
     * Relasi ke model User (satu role dimiliki banyak user).
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }
}