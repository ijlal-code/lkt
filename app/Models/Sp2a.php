<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sp2a extends Model
{
    use HasFactory;

    protected $fillable = [
        'nomor_sp2a',
        'tanggal_surat',
        'kepada_nama',    // Sekarang menyimpan Array/JSON
        // 'kepada_email', // DIHAPUS
        'dari_nama',
        'perihal',
        'dasar_surat',
        'isi_surat',
        'penanda_tangan_nama',
        'tembusan',
        'status',
        'current_step',
        'catatan_koreksi',
        'approved_sm_at',
        'approved_smqa_at',
        'approved_gm_at'
    ];

    protected $casts = [
        'tanggal_surat' => 'date',
        'approved_sm_at' => 'datetime',
        'approved_smqa_at' => 'datetime',
        'approved_gm_at' => 'datetime',
        'tembusan' => 'array',
        'kepada_nama' => 'array', // PENTING: Cast ke array otomatis
    ];
}