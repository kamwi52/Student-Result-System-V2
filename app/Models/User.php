<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * === THE FIX IS HERE ===
     * We remove 'profile_photo_path' to prevent the application from trying
     * to update a database column that does not exist.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'email_verified_at',
        // 'profile_photo_path', // <-- THIS LINE HAS BEEN REMOVED
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = ['password', 'remember_token'];

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

    // --- Student-specific relationships ---

    public function results(): HasMany
    {
        return $this->hasMany(Result::class, 'user_id');
    }

    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class, 'user_id');
    }
    
    public function classSection(): HasOneThrough
    {
        return $this->hasOneThrough(
            ClassSection::class,
            Enrollment::class,
            'user_id',
            'id',
            'id',
            'class_section_id'
        );
    }


    // --- Teacher-specific relationships ---

    public function taughtClasses(): BelongsToMany
    {
        return $this->belongsToMany(ClassSection::class, 'class_section_teacher');
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(Assignment::class, 'teacher_id');
    }

    public function qualifiedSubjects(): BelongsToMany
    {
        return $this->belongsToMany(Subject::class, 'subject_user', 'user_id', 'subject_id');
    }
}