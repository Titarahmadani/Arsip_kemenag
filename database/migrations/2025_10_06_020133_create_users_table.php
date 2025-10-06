<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Jalankan migrasi.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('username', 100)->unique();
            $table->string('email', 100)->unique();
            $table->string('password');

            // Relasi ke roles
            $table->foreignId('role_id')
                ->constrained('roles')
                ->onDelete('cascade');

            // Relasi ke divisions
            $table->foreignId('division_id')
                ->nullable()
                ->constrained('divisions')
                ->onDelete('set null');

            // Foto profil
            $table->string('profile_photo')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Undo migrasi.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};