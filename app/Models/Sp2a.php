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
        'current_step', // Tahapan workflow
        'catatan_koreksi', // Komentar revisi
        'approved_sm_at',
        'approved_smqa_at',
        'approved_gm_at',
        'approved_at'
    ];
}