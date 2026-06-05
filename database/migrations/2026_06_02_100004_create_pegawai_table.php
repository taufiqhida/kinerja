<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pegawai', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('nik')->unique();
            $table->string('nama_lengkap');
            $table->string('pangkat_golongan')->nullable();
            $table->foreignId('jabatan_id')->nullable()->constrained('jabatan')->nullOnDelete();
            $table->foreignId('unit_kerja_id')->nullable()->constrained('unit_kerja')->nullOnDelete();
            $table->foreignId('kepala_id')->nullable()->constrained('kepala')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pegawai');
    }
};
