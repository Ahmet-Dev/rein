<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Security extends Model
{
    use HasFactory;

    protected $fillable = ['building_id', 'security_type', 'description'];

    public function building()
    {
        return $this->belongsTo(Building::class);
    }
}
