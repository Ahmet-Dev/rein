<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComplianceAudit extends Model
{
    use HasFactory;

    protected $fillable = ['audit_type', 'audit_date', 'user_id', 'status'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

