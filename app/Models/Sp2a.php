<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sp2a extends Model
{
    use HasFactory;

   protected $fillable = [
    'nomor_sp2a', 'tanggal_surat', 
    'kepada_nama', 'kepada_email', 
    'dari_nama',
    'email_auditor', 'email_k3', 'email_staff', 'email_atasan', // <-- Baru
    'cc_nama', 'cc_email', // Sisa yang lama (opsional)
    'perihal', 'dasar_surat', 'isi_surat',
    'penanda_tangan_nama', 'status', 'approved_at'
];

    protected $casts = [
        'tanggal_surat' => 'date',
        'approved_at' => 'datetime',
    ];
}