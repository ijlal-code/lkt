<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sp2a extends Model
{
    use HasFactory;

    protected $fillable = [
        'ltk_id',
        'nomor_sp2a',
        'tanggal_sp2a',
        'kepada',
        'dasar_peringatan',
        'isi_peringatan',
        'email_auditee',
        'email_auditor',
        'status',
        'tanggal_approved',
    ];

    protected $casts = [
        'tanggal_sp2a' => 'date',
        'tanggal_approved' => 'date',
    ];

    /**
     * Relasi: Setiap SP2A milik satu LKT
     */
    public function ltk()
    {
        return $this->belongsTo(Ltk::class, 'ltk_id');
    }
}