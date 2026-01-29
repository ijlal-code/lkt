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
            
            // --- HEADER SURAT ---
            $table->string('nomor_sp2a')->nullable(); // Kosong saat Draft, Terisi saat GM Approve
            $table->date('tanggal_surat');
            
            // --- PIHAK TERKAIT (Input Manual) ---
            $table->string('kepada_nama');        // Nama Auditi (Manual)
            $table->string('kepada_email')->nullable(); // Email Auditi (Opsional)
            $table->string('dari_nama');          // Nama Pengirim (Staff/Admin)
            $table->string('penanda_tangan_nama'); // Nama GM Internal Audit
            
            // --- KONTEN SURAT ---
            $table->string('perihal')->default('Surat Peringatan 2A');
            $table->string('dasar_surat');        // Contoh: LKT/2026/001
            $table->longText('isi_surat');        // Isi dari CKEditor
            
            // --- TEMBUSAN (Dynamic List) ---
            // Menggunakan tipe JSON agar bisa menyimpan banyak tembusan (array) dalam satu kolom
            $table->json('tembusan')->nullable(); 
            
            // --- WORKFLOW & STATUS ---
            $table->string('status')->default('Draft'); 
            // Urutan Step: staff -> sm -> smqa -> gm -> finished
            $table->enum('current_step', ['staff', 'sm', 'smqa', 'gm', 'finished'])->default('staff');
            
            // Kolom untuk menyimpan catatan revisi jika dikembalikan
            $table->text('catatan_koreksi')->nullable();

            // --- TIMESTAMP APPROVAL ---
            $table->dateTime('approved_sm_at')->nullable();   // Waktu SM Setuju
            $table->dateTime('approved_smqa_at')->nullable(); // Waktu SM QA Setuju
            $table->dateTime('approved_gm_at')->nullable();   // Waktu GM Setuju (Final)

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Pastikan nama tabel sesuai dengan yang dibuat di up()
        Schema::dropIfExists('sp2as');
    }
};