<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'email_verified_at',
        'student_id',
        'profile_photo_path',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // --- Student-specific relationships ---
    public function results(): HasMany
    {
        return $this->hasMany(Result::class, 'user_id');
    }

    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class, 'user_id');
    }

    // === NEW RELATIONSHIP FOR TEACHERS ===
    /**
     * Get all assignments for this user (as a teacher).
     * An assignment is a specific subject in a specific class.
     */
    public function assignments(): HasMany
    {
        return $this->hasMany(Assignment::class);
    }
}