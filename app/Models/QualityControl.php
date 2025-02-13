<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QualityControl extends Model
{
    use HasFactory;

    protected $fillable = ['test_name', 'description', 'product_id', 'result'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}

