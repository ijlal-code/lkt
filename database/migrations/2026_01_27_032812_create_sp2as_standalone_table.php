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
    Schema::create('sp2as', function (Blueprint $table) {
        $table->id();
        
        // --- HEADER ---
        $table->string('nomor_sp2a')->nullable(); // Generate saat approve
        $table->date('tanggal_surat');
        
        // --- TUJUAN (Diambil dari tabel Contact) ---
        $table->string('kepada_nama'); // Nama Auditee
        $table->string('kepada_email'); // Email Auditee (disimpan snapshot-nya)
        
        $table->string('cc_nama')->nullable(); // Nama Auditor/Atasan
        $table->string('cc_email')->nullable(); // Email CC
        
        // --- KONTEN ---
        $table->string('perihal')->default('Surat Peringatan 2A');
        $table->text('dasar_surat'); // Dasarnya apa (ketik manual)
        $table->text('isi_surat');   // Isi teguran
        
        // --- STATUS ---
        $table->string('status')->default('Draft'); // Draft, Approved
        $table->dateTime('approved_at')->nullable();

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sp2as_standalone');
    }
};
