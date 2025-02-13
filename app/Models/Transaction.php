<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'account_id',
        'invoice_id',
        'amount',
        'final_amount',
        'tax_amount',
        'deduction_amount',
        'commission_amount',
        'transaction_type',
        'description'
    ];

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }
}


