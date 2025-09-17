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
            $table->string('nomor_arsip');
            $table->string('nama_arsip');
            $table->string('kode_klasifikasi');
            $table->date('tgl_upload');
            $table->string('pencipta_arsip');
            $table->string('unit_pengolahan');
            $table->string('lokasi_arsip');
            $table->string('keterangan');
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