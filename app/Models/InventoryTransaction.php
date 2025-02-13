<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'inventory_id',
        'transaction_type',
        'quantity'
    ];

    public function inventory()
    {
        return $this->belongsTo(Inventory::class);
    }
}

