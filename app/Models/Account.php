<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;

    protected $fillable = ['account_name', 'balance', 'account_type'];

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}

