<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShootingSchedule extends Model
{
    use HasFactory;

    protected $fillable = ['project_id', 'title', 'start_time', 'end_time', 'assigned_users'];

    // Mengonversi data JSON di database menjadi array PHP secara otomatis
    protected $casts = [
        'assigned_users' => 'array',
        'start_time' => 'datetime',
        'end_time' => 'datetime'
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}