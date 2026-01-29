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
        'kepada_nama',
        'kepada_email',
        'dari_nama',
        'perihal',
        'dasar_surat',
        'isi_surat',
        'penanda_tangan_nama',
        'email_auditor',
        'email_k3',
        'email_staff',
        'email_atasan',
        'status',
        'current_step',
        'catatan_koreksi',
        'approved_sm_at',
        'approved_smqa_at',
        'approved_gm_at',
        'approved_at'
    ];

    // PERBAIKAN: Tambahkan ini agar tanggal_surat dibaca sebagai Objek Tanggal
    protected $casts = [
        'tanggal_surat' => 'date',
        'approved_sm_at' => 'datetime',
        'approved_smqa_at' => 'datetime',
        'approved_gm_at' => 'datetime',
        'approved_at' => 'datetime',
    ];
}