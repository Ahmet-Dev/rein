<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupportTicket extends Model
{
    use HasFactory;

    protected $fillable = ['customer_id', 'subject', 'message', 'status'];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}

