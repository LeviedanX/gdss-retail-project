<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evaluation extends Model
{
    use HasFactory;
    
    // KUNCI: Hanya kolom ini yang boleh diisi
    protected $fillable = ['user_id', 'candidate_id', 'criteria_id', 'score'];

    public function candidate() { return $this->belongsTo(Candidate::class); }
    public function user() { return $this->belongsTo(User::class); }
}