<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Maintenance extends Model
{
    use HasFactory;

    protected $fillable = ['description', 'building_id', 'scheduled_at', 'status'];

    public function building()
    {
        return $this->belongsTo(Building::class);
    }
}
