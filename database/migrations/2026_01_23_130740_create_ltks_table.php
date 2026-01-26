<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ltks', function (Blueprint $table) {
            $table->id('ID_LTK');

            /* =====================================================
             * A. IDENTITAS DOKUMEN
             * ===================================================== */
            $table->string('Kode_Form')->default('FP/ST/SYM/001');
            $table->string('Judul_Dokumen')->default('LAPORAN TEMUAN KETIDAKSESUAIAN (LTK)');
            $table->string('LTK_No')->nullable();
            $table->string('Status_Dokumen')->nullable(); // Baru / Revisi

            /* =====================================================
             * B. INFORMASI AUDIT
             * ===================================================== */
            $table->string('Tipe_Audit_INT_OT')->nullable(); // Internal / External / Other
            $table->string('Nomor_Prosedur')->nullable();
            $table->date('Tanggal_Audit')->nullable();
            $table->string('Tahun')->nullable();

            /* =====================================================
             * C. PENERBIT
             * ===================================================== */
            $table->string('Penerbit_1')->nullable();
            $table->string('Penerbit_2')->nullable();
            $table->string('Penerbit_3')->nullable();

            /* =====================================================
             * D. TUJUAN & UNIT TERKAIT
             * ===================================================== */
            $table->string('Kepada')->nullable();
            $table->string('Dept_Proses')->nullable();
            $table->date('Tanggal_Laporan')->nullable();

            /* =====================================================
             * E. SUMBER TEMUAN (CHECKLIST)
             * ===================================================== */
            $table->boolean('Sumber_Audit_Internal')->default(false);
            $table->boolean('Sumber_SMST')->default(false);
            $table->boolean('Sumber_Komplain_Pelanggan')->default(false);
            $table->boolean('Sumber_Proses_Perbaikan')->default(false);
            $table->boolean('Sumber_Tinjauan_Manajemen')->default(false);
            $table->boolean('Sumber_Lainnya')->default(false);
            $table->string('Sumber_Lainnya_Text')->nullable();

            /* =====================================================
             * F. URAIAN TEMUAN
             * ===================================================== */
            $table->text('Gambaran_Ketidaksesuaian')->nullable();
            $table->string('Lokasi')->nullable();
            $table->text('Bukti_Objektif')->nullable();

            /* =====================================================
             * G. REFERENSI STANDAR (CHECKLIST + KLAUSUL)
             * ===================================================== */
            $table->boolean('Ref_ISO_9001')->default(false);
            $table->string('ISO_9001_Klausul')->nullable();

            $table->boolean('Ref_ISO_14001')->default(false);
            $table->string('ISO_14001_Klausul')->nullable();

            $table->boolean('Ref_SMK3')->default(false);
            $table->string('SMK3_Elemen')->nullable();

            $table->boolean('Ref_ISO_45001')->default(false);
            $table->string('ISO_45001_Klausul')->nullable();

            $table->boolean('Ref_LAB_17025')->default(false);
            $table->string('LAB_17025_Klausul')->nullable();

            $table->boolean('Ref_ISO_50001')->default(false);
            $table->string('ISO_50001_Klausul')->nullable();

            $table->boolean('Ref_SMKP_Minerba')->default(false);
            $table->string('SMKP_Minerba_Elemen')->nullable();

            $table->boolean('Ref_ISO_37001')->default(false);
            $table->string('ISO_37001_Elemen')->nullable();

            /* =====================================================
             * H. AUDITOR
             * ===================================================== */
            $table->string('Paraf_Auditor')->nullable();
            $table->string('Inisial_Auditor')->nullable();

            /* =====================================================
             * I. ANALISIS & TINDAKAN PERBAIKAN
             * ===================================================== */
            $table->text('Akar_Masalah')->nullable();
            $table->text('Rencana_Tindakan_Perbaikan')->nullable();
            $table->date('Tanggal_Selesai_Tindakan')->nullable();

            /* =====================================================
             * J. AUDITEE
             * ===================================================== */
            $table->string('Auditee_Nama')->nullable();
            $table->string('Paraf_Auditee')->nullable();
            $table->string('Inisial_Auditee')->nullable();

            /* =====================================================
             * K. VERIFIKASI
             * ===================================================== */
            $table->date('Tanggal_Verifikasi')->nullable();
            $table->text('Komentar_Verifikasi')->nullable();
            $table->string('Hasil_Verifikasi')->nullable(); // contoh: Sudah Close

            /* =====================================================
             * L. STATUS TEMUAN
             * ===================================================== */
            $table->string('Status_Temuan')->nullable(); // Selesai / Lanjut
            $table->string('Kategori_Temuan')->nullable(); // Fatality / Major / Minor / Observasi

            /* =====================================================
             * M. PENUTUP
             * ===================================================== */
            $table->string('Penerbit_Penutup_Paraf')->nullable();
            $table->string('Penerbit_Penutup_Inisial')->nullable();
            $table->string('Dilanjutkan_Ke_LTK_No')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ltks');
    }
};
