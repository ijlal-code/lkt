<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sp2as', function (Blueprint $table) {
            $table->string('dari_nama')->nullable()->after('kepada_email'); // Pengirim
            $table->string('penanda_tangan_nama')->nullable()->after('isi_surat'); // Nama di TTD
        });
    }

    public function down(): void
    {
        Schema::table('sp2as', function (Blueprint $table) {
            $table->dropColumn(['dari_nama', 'penanda_tangan_nama']);
        });
    }
};