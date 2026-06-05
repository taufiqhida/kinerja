<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('perilaku_indikator', function (Blueprint $table) {
            $table->id();
            $table->foreignId('perilaku_master_id')->constrained('perilaku_master')->cascadeOnDelete();
            $table->text('deskripsi_indikator');
            $table->integer('urutan')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('perilaku_indikator');
    }
};
