<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    use HasFactory;

    protected $fillable = ['customer_id', 'feedback', 'rating'];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}

