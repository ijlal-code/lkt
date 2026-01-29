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
        'kepada_nama',    // Input Teks Manual
        'kepada_email',   // Input Teks Manual
        'dari_nama',
        'perihal',
        'dasar_surat',
        'isi_surat',
        'penanda_tangan_nama',
        'tembusan',       // Field Baru (Array List)
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
        'tembusan' => 'array', // PENTING: Agar otomatis jadi Array saat diambil
    ];
}