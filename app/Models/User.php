<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // --- RELATIONSHIPS ---

    // Get the classes that this user teaches.
    public function taughtClasses(): HasMany
    {
        // Points to the ClassSection model and uses the 'teacher_id' foreign key.
        return $this->hasMany(ClassSection::class, 'teacher_id');
    }

    // Get the classes this user is enrolled in (if they are a student).
    public function enrolledClasses(): BelongsToMany
    {
        // CORRECTED: This now points to the correct ClassSection model.
        return $this->belongsToMany(ClassSection::class, 'class_student', 'user_id', 'class_id');
    }
}