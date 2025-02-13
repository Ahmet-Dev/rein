<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AIAnalysis extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'analysis_type', 'result', 'status', 'created_at'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}


