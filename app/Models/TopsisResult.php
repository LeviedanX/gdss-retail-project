<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TopsisResult extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'candidate_id', 'preference_value', 'rank'];

    public function candidate() { return $this->belongsTo(Candidate::class); }
    public function user() { return $this->belongsTo(User::class); }
}