<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    // Mengizinkan kolom-kolom ini diisi data secara massal
    protected $fillable = ['title', 'description', 'script_path', 'storyboard_path', 'creator_id'];

    // Relasi: Mengetahui siapa Ketua yang membuat proyek ini
    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    // Relasi: Mendapatkan semua Anggota yang ada di proyek ini
    public function members()
    {
        return $this->belongsToMany(User::class, 'project_members', 'project_id', 'user_id')
                    ->withPivot('role', 'permissions')
                    ->withTimestamps();
    }

    // Relasi: Mendapatkan semua file progress dari proyek ini
    public function progresses()
    {
        return $this->hasMany(ProjectProgress::class);
    }
}
