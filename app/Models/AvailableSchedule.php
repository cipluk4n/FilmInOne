<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AvailableSchedule extends Model
{
    use HasFactory;

    protected $fillable = ['project_id', 'user_id', 'start_time', 'end_time'];

    // TAMBAHKAN FUNGSI RELASI INI:
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}