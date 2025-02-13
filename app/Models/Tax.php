<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tax extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'rate',
        'type',
        'is_active'
    ];

    public static function getActiveRates()
    {
        return [
            'tax' => self::where('type', 'tax')->where('is_active', true)->sum('rate'),
            'deduction' => self::where('type', 'deduction')->where('is_active', true)->sum('rate'),
            'commission' => self::where('type', 'commission')->where('is_active', true)->sum('rate'),
        ];
    }
}

