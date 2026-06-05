<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penilaian_hasil', function (Blueprint $table) {
            $table->id();
            $table->foreignId('indikator_kinerja_id')->constrained('indikator_kinerja')->cascadeOnDelete();
            $table->foreignId('kepala_id')->constrained('kepala')->cascadeOnDelete();
            $table->enum('nilai', ['perlu_perbaikan', 'sesuai_ekspektasi', 'di_atas_ekspektasi']);
            $table->integer('nilai_angka'); // 70, 85, or 100
            $table->timestamps();

            $table->unique(['indikator_kinerja_id', 'kepala_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penilaian_hasil');
    }
};
