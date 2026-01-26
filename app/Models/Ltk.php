<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ltk extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        // 1. Header & Identitas
        'nomor_lkt',
        'tanggal',
        'penerbit_1',
        'penerbit_2',
        'penerbit_3',

        // 2. Sumber Temuan
        'sumber',
        'sumber_lainnya_text',

        // 3. Target (Kepada)
        'kepada',
        'unit_kerja',

        // 4. Detail Ketidaksesuaian
        'ketidaksesuaian',
        'lokasi',
        'bukti_objektif',
        'inisial_auditor',

        // 5. Referensi Standar (ISO/Klausul)
        'iso_9001_klausul',
        'iso_14001_klausul',
        'smk3_elemen',
        'iso_45001_klausul',
        'lab_17025_klausul',
        'iso_50001_klausul',
        'smkp_minerba_elemen',
        'iso_37001_elemen',

        // 6. Analisa & Tindakan Perbaikan
        'akar_penyebab',
        'tindakan_perbaikan',
        'auditee_nama',
        'target_penyelesaian',

        // 7. Verifikasi & Status
        'verifikasi_tindakan',
        'status', // Lanjut / Selesai
        'kategori_temuan', // Major / Minor / dll
        'tanggal_verifikasi',
        
        // 8. Footer
        'dilanjutkan_ke_ltk_no',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'tanggal' => 'date',
        'target_penyelesaian' => 'date',
        'tanggal_verifikasi' => 'date',
    ];
}