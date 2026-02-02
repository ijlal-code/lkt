<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Ubah struktur tabel sp2as
        Schema::table('sp2as', function (Blueprint $table) {
            $table->dropColumn('kepada_email'); // Hapus kolom email
            // Ubah kepada_nama agar bisa menampung JSON (Array)
            // Kita drop dulu kolom lama lalu buat baru sebagai JSON/TEXT
            $table->dropColumn('kepada_nama');
        });

        Schema::table('sp2as', function (Blueprint $table) {
            $table->json('kepada_nama')->after('tanggal_surat'); // Buat ulang sebagai JSON
        });

        // 2. Buat tabel settings untuk menyimpan Nama GM
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique(); // Misal: 'gm_internal_audit'
            $table->text('value')->nullable(); // Misal: 'Budi Santoso'
            $table->timestamps();
        });

        // Insert data default untuk GM
        DB::table('settings')->insert([
            'key' => 'gm_name',
            'value' => 'Nama GM Belum Diatur',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
        Schema::table('sp2as', function (Blueprint $table) {
            $table->string('kepada_email')->nullable();
            $table->dropColumn('kepada_nama');
        });
        Schema::table('sp2as', function (Blueprint $table) {
            $table->string('kepada_nama');
        });
    }
};