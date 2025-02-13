<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Survey extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'created_by',
        'start_date',
        'end_date'
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}

