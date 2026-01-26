<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::create('ltks', function (Blueprint $table) {
        $table->id();
        // --- HEADER ---
        $table->string('nomor_lkt')->unique();
        $table->date('tanggal'); // Tanggal Laporan
        
        // --- IDENTITAS PENERBIT ---
        $table->string('penerbit_1')->nullable();
        $table->string('penerbit_2')->nullable();
        $table->string('penerbit_3')->nullable();
        
        // --- SUMBER (Checklist) ---
        // Simpan string: 'Audit Internal', 'SMST', 'Komplain Pelanggan', dll.
        $table->string('sumber')->nullable(); 
        $table->string('sumber_lainnya_text')->nullable(); // Jika pilih lainnya
        
        $table->string('kepada'); // Unit Kerja/Auditee
        $table->string('unit_kerja'); // Dept / Proses
        
        // --- URAIAN KETIDAKSESUAIAN ---
        $table->text('ketidaksesuaian');
        $table->string('lokasi')->nullable();
        $table->text('bukti_objektif')->nullable();
        $table->string('inisial_auditor')->nullable();
        
        // --- REFERENSI (Klausul) ---
        $table->string('iso_9001_klausul')->nullable();
        $table->string('iso_14001_klausul')->nullable();
        $table->string('smk3_elemen')->nullable();
        $table->string('iso_45001_klausul')->nullable();
        $table->string('lab_17025_klausul')->nullable();
        $table->string('iso_50001_klausul')->nullable();
        $table->string('smkp_minerba_elemen')->nullable();
        $table->string('iso_37001_elemen')->nullable();
        
        // --- ANALISA & TINDAKAN ---
        $table->text('akar_penyebab');
        $table->text('tindakan_perbaikan');
        $table->string('auditee_nama')->nullable();
        $table->date('target_penyelesaian');
        
        // --- VERIFIKASI ---
        $table->text('verifikasi_tindakan')->nullable();
        $table->string('status')->default('Lanjut'); // Selesai / Lanjut
        $table->string('kategori_temuan')->nullable(); // Major/Minor/dll
        $table->date('tanggal_verifikasi')->nullable();
        
        $table->string('dilanjutkan_ke_ltk_no')->nullable();

        $table->timestamps();
    });
}

    public function down(): void
    {
        Schema::dropIfExists('ltks');
    }
};
