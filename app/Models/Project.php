<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'created_by',
        'start_date',
        'end_date',
        'status'
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'project_user');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function tasks()
    {
        return $this->belongsToMany(Task::class, 'project_task');
    }
}
