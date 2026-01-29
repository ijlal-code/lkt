<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('sp2as', function (Blueprint $table) {
        $table->string('nomor_sp2a')->nullable()->change(); // Boleh kosong di awal
        $table->enum('current_step', ['staff', 'sm', 'smqa', 'gm', 'finished'])->default('staff');
        $table->text('catatan_koreksi')->nullable();
        $table->timestamp('approved_sm_at')->nullable();
        $table->timestamp('approved_smqa_at')->nullable();
        $table->timestamp('approved_gm_at')->nullable();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
