<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectMember extends Model
{
    protected $fillable = ['project_id', 'user_id', 'role', 'permissions'];

    // Karena kolom 'permissions' bertipe JSON, kita ubah otomatis jadi Array di PHP
    protected $casts = [
        'permissions' => 'array',
    ];
}