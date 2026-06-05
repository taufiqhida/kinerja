<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penilaian_perilaku', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pegawai_id')->constrained('pegawai')->cascadeOnDelete();
            $table->foreignId('kepala_id')->constrained('kepala')->cascadeOnDelete();
            $table->foreignId('periode_id')->constrained('periode_penilaian')->cascadeOnDelete();
            $table->foreignId('perilaku_master_id')->constrained('perilaku_master')->cascadeOnDelete();
            $table->text('ekspektasi_pimpinan')->nullable();
            $table->text('feedback')->nullable();
            $table->enum('nilai', ['perlu_perbaikan', 'sesuai_ekspektasi', 'di_atas_ekspektasi']);
            $table->integer('nilai_angka'); // 70, 85, or 100
            $table->timestamps();

            $table->unique(['pegawai_id', 'periode_id', 'perilaku_master_id'], 'penilaian_perilaku_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penilaian_perilaku');
    }
};
