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
        Schema::table('penilaian_hasil', function (Blueprint $table) {
            $table->text('ekspektasi_pimpinan')->nullable()->after('kepala_id');
            $table->text('feedback')->nullable()->after('ekspektasi_pimpinan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('penilaian_hasil', function (Blueprint $table) {
            $table->dropColumn(['ekspektasi_pimpinan', 'feedback']);
        });
    }
};
