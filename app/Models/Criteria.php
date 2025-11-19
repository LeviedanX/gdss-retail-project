<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Criteria extends Model
{
    use HasFactory;
    
    // KUNCI: Hanya kolom ini yang boleh diisi
    protected $fillable = [
        'code', 
        'name', 
        'type', 
        'weight'
    ];
}