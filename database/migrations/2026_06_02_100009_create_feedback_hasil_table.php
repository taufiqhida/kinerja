<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('feedback_hasil', function (Blueprint $table) {
            $table->id();
            $table->foreignId('indikator_kinerja_id')->constrained('indikator_kinerja')->cascadeOnDelete();
            $table->foreignId('kepala_id')->constrained('kepala')->cascadeOnDelete();
            $table->text('isi_feedback');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('feedback_hasil');
    }
};
