<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectProgress extends Model
{
    protected $fillable = ['project_id', 'user_id', 'title', 'description', 'file_path', 'file_type'];

    // Relasi: Mengetahui siapa anggota yang mengunggah berkas ini
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}