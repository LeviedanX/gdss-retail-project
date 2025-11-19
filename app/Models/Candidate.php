<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Candidate extends Model
{
    use HasFactory;
    
    // KUNCI: Hanya kolom ini yang boleh diisi via form (Mass Assignment)
    protected $fillable = [
        'name', 
        'age', 
        'experience_year'
    ];
}