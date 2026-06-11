<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ttd_penilaian', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pegawai_id');
            $table->unsignedBigInteger('kepala_id');
            $table->unsignedBigInteger('periode_id');

            // Token unik untuk validasi QR code
            $table->string('token', 64)->unique();

            // Status TTD masing-masing pihak
            $table->boolean('ttd_kepala')->default(true);   // true = kepala sudah tanda tangan (saat simpan)
            $table->boolean('ttd_pegawai')->default(false); // false = menunggu pegawai konfirmasi

            // Timestamp masing-masing TTD
            $table->timestamp('kepala_signed_at')->nullable();
            $table->timestamp('pegawai_signed_at')->nullable();

            $table->timestamps();

            // Foreign keys dengan nama tabel yang benar
            $table->foreign('pegawai_id')->references('id')->on('pegawai')->cascadeOnDelete();
            $table->foreign('kepala_id')->references('id')->on('kepala')->cascadeOnDelete();
            $table->foreign('periode_id')->references('id')->on('periode_penilaian')->cascadeOnDelete();

            // Satu record per kombinasi pegawai-kepala-periode
            $table->unique(['pegawai_id', 'kepala_id', 'periode_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ttd_penilaian');
    }
};
