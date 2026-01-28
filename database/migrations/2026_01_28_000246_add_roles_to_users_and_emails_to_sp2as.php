<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        

        // 2. Tambah Kolom Email Tujuan Spesifik ke SP2A
        Schema::table('sp2as', function (Blueprint $table) {
            // Kita simpan emailnya saja agar saat User dihapus, history surat tetap ada
            $table->string('email_auditor')->nullable()->after('kepada_email');
            $table->string('email_k3')->nullable()->after('email_auditor');
            $table->string('email_staff')->nullable()->after('email_k3');
            $table->string('email_atasan')->nullable()->after('email_staff');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role');
        });
        Schema::table('sp2as', function (Blueprint $table) {
            $table->dropColumn(['email_auditor', 'email_k3', 'email_staff', 'email_atasan']);
        });
    }
};