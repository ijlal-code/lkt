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

    
}