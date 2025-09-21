<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Models\Role;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil role admin
        $adminRole = Role::where('name', 'admin')->first();

        // Buat akun admin default jika belum ada
        User::firstOrCreate(
            ['email' => 'admin@example.com'], // cek berdasarkan email
            [
                'username' => 'Administrator',
                'password' => Hash::make('admin12345'), // ganti password default sesuai kebutuhan
                'role_id' => $adminRole->id,
                'division_id' => null, // admin tidak butuh divisi
                'profile_photo' => null,
            ]
        );
    }
}