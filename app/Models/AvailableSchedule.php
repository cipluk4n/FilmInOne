<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AvailableSchedule extends Model
{
    protected $fillable = ['user_id', 'project_id', 'start_time', 'end_time'];
}