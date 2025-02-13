<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoyaltyProgram extends Model
{
    use HasFactory;

    protected $fillable = ['customer_id', 'points', 'membership_level'];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}

