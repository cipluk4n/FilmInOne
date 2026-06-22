<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    // Melambangkan bahwa satu User bisa ikut di banyak proyek
    public function projects()
    {
        return $this->belongsToMany(Project::class, 'project_members', 'user_id', 'project_id')
                    ->withPivot('role', 'permissions')
                    ->withTimestamps();
    }

    // Melambangkan satu User bisa punya banyak jadwal luang
    public function availableSchedules()
    {
        return $this->hasMany(AvailableSchedule::class);
    }
}
