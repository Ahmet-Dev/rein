<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobPosition extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'salary_range_min', 'salary_range_max'];

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }
}
