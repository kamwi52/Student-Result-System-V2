<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOneThrough; // <-- ADD THIS LINE

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'role', 'email_verified_at', 'profile_photo_path',
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
    
    /**
     * === FIX: Get the student's primary class section through their enrollment. ===
     * This defines the missing 'classSection' relationship needed for the report card.
     */
    public function classSection(): HasOneThrough
    {
        // This tells Laravel: "To find this user's ClassSection, look through the Enrollment model."
        // It connects users.id -> enrollments.user_id -> enrollments.class_section_id -> class_sections.id
        return $this->hasOneThrough(
            ClassSection::class,    // The final model we want
            Enrollment::class,      // The intermediate model
            'user_id',              // Foreign key on 'enrollments' table (for User)
            'id',                   // Foreign key on 'class_sections' table (for Enrollment)
            'id',                   // Local key on 'users' table
            'class_section_id'      // Local key on 'enrollments' table
        );
    }


    // --- Teacher-specific relationships ---

    /**
     * Get the classes that this user (teacher) is assigned to as a general class teacher.
     */
    public function taughtClasses(): BelongsToMany
    {
        return $this->belongsToMany(ClassSection::class, 'class_section_teacher');
    }

    /**
     * Get the specific assignments that this user (teacher) is responsible for.
     */
    public function assignments(): HasMany
    {
        return $this->hasMany(Assignment::class, 'teacher_id');
    }

    /**
     * Get the subjects this user (teacher) is qualified to teach.
     */
    public function qualifiedSubjects(): BelongsToMany
    {
        return $this->belongsToMany(Subject::class, 'subject_user', 'user_id', 'subject_id');
    }
}