<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Building extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'location', 'facility_id'];

    public function facility()
    {
        return $this->belongsTo(Facility::class);
    }

    public function maintenance()
    {
        return $this->hasMany(Maintenance::class);
    }

    public function security()
    {
        return $this->hasOne(Security::class);
    }

    public function assets()
    {
        return $this->hasMany(Asset::class);
    }
}
