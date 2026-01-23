<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ltk extends Model
{
    use HasFactory;

    protected $primaryKey = 'ID_LTK'; // Sesuai migration
    
    // Izinkan semua field diisi (mass assignment)
    protected $guarded = [];

    // Helper untuk mengubah checkbox database (1/0) jadi boolean di view jika perlu
    protected $casts = [
        'Ref_ISO_9001' => 'boolean',
        'Ref_ISO_14001' => 'boolean',
        'Ref_SMK3' => 'boolean',
        'Ref_ISO_45001' => 'boolean',
        'Ref_LAB_17025' => 'boolean',
        'Ref_ISO_50001' => 'boolean',
        'Ref_SMKP_Minerba' => 'boolean',
        'Ref_ISO_37001' => 'boolean',
    ];
}