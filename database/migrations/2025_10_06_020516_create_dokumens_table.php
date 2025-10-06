<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('dokumens', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_arsip', 100);
            $table->string('nama_arsip', 100);
            $table->string('kode_klasifikasi', 50);
            $table->date('tgl_upload');
            $table->string('pencipta_arsip', 100);
            $table->string('unit_pengolahan', 100);
            $table->string('lokasi_arsip', 100);
            $table->text('keterangan');
            $table->integer('nomor_box');
            $table->enum('ext',['doc','docx','xls','xlsx','pdf']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dokumens');
    }
};