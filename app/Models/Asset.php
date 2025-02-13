<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'building_id', 'value', 'status'];

    public function building()
    {
        return $this->belongsTo(Building::class);
    }
}
