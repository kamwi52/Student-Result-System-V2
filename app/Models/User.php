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
        return $this->hasMany(Result::class, 'user_id'); // Assuming 'user_id' is the student's ID in results
    }

    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class, 'user_id'); // Assuming 'user_id' is the student's ID in enrollments
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
        return $this->hasMany(Assignment::class, 'teacher_id'); // Assuming 'teacher_id' is the foreign key in the 'assignments' table
    }

    /**
     * === NEW: Get the subjects this user (teacher) is qualified to teach. ===
     */
    public function qualifiedSubjects(): BelongsToMany
    {
        // This links to the 'subject_user' pivot table
        return $this->belongsToMany(Subject::class, 'subject_user', 'user_id', 'subject_id');
    }
}