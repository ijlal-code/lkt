<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ltks', function (Blueprint $table) {
            $table->id('ID_LTK'); // Primary Key
            
            // Header Dokumen
            $table->string('Kode_Form')->nullable()->default('FRM-IA-01');
            $table->string('Judul_Dokumen')->nullable()->default('LAPORAN TEMUAN KETIDAKSESUAIAN (LTK)');
            $table->string('LTK_No')->nullable();
            
            // Informasi Audit
            $table->string('Tipe_Audit_INT_OT')->nullable(); // Internal / Other
            $table->string('Nomor_Prosedur')->nullable();
            $table->date('Tanggal_Audit')->nullable();
            $table->string('Tahun')->nullable();
            
            // Penerbit (Checklist/Nama)
            $table->string('Penerbit_1')->nullable();
            $table->string('Penerbit_2')->nullable();
            $table->string('Penerbit_3')->nullable();
            
            // Tujuan
            $table->string('Kepada')->nullable(); // Unit Kerja/Auditee
            $table->string('Dept_Proses')->nullable();
            $table->date('Tanggal_Laporan')->nullable();
            
            // Sumber Temuan
            $table->string('Sumber_Temuan')->nullable();
            $table->string('Sumber_Lainnya_Text')->nullable();
            
            // Uraian Temuan
            $table->text('Gambaran_Ketidaksesuaian')->nullable();
            $table->string('Lokasi')->nullable();
            $table->text('Bukti_Objektif')->nullable();
            
            // Referensi Standar (Checkbox)
            $table->boolean('Ref_ISO_9001')->default(0);
            $table->boolean('Ref_ISO_14001')->default(0);
            $table->boolean('Ref_SMK3')->default(0);
            $table->boolean('Ref_ISO_45001')->default(0);
            $table->boolean('Ref_LAB_17025')->default(0);
            $table->boolean('Ref_ISO_50001')->default(0);
            $table->boolean('Ref_SMKP_Minerba')->default(0);
            $table->boolean('Ref_ISO_37001')->default(0);
            
            // Auditor Info
            $table->string('Paraf_Auditor')->nullable(); // Simpan Nama/Inisial
            $table->string('Inisial_Auditor')->nullable();
            
            // Analisa & Tindakan
            $table->text('Akar_Masalah')->nullable();
            $table->text('Rencana_Tindakan')->nullable();
            $table->date('Tgl_Selesai_Tindakan')->nullable();
            
            // Auditee Info
            $table->string('Auditee_Nama')->nullable();
            $table->string('Paraf_Auditee')->nullable();
            $table->string('Inisial_Auditee')->nullable();
            
            // Verifikasi
            $table->date('Tgl_Verifikasi')->nullable();
            $table->text('Komentar_Verifikasi')->nullable();
            $table->string('Status_Temuan')->nullable(); // Open/Closed
            $table->string('Kategori_Temuan')->nullable(); // Major/Minor/Obs
            
            // Penutup
            $table->string('Penutup_Penerbit_Paraf')->nullable();
            $table->string('Penutup_Penerbit_Inisial')->nullable();
            $table->string('Lanjut_Ke_LTK_No')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ltks');
    }
};