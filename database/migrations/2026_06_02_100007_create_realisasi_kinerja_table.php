<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('realisasi_kinerja', function (Blueprint $table) {
            $table->id();
            $table->foreignId('indikator_kinerja_id')->constrained('indikator_kinerja')->cascadeOnDelete();
            $table->integer('jumlah_realisasi')->default(0);
            $table->text('keterangan')->nullable();
            $table->date('tanggal_realisasi');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('realisasi_kinerja');
    }
};
