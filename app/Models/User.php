<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

// IMPORTANT: This 'use' statement is now added
use App\Models\ClassSection;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role', // CORRECTED: 'role' is now included
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

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

    /**
     * CORRECTED & CLEANED UP: Get the classes taught by this user (if they are a teacher).
     * This is the relationship used by the Teacher's Dashboard.
     */
    public function taughtClasses(): HasMany
{
    // CORRECTED: This now correctly looks for the 'user_id' column
    // which is the actual foreign key in your 'classes' table.
    return $this->hasMany(ClassSection::class, 'user_id');
}
    /**
     * Get the classes this user is enrolled in (if they are a student).
     * The pivot table 'class_student' connects a User to a ClassSection.
     */
    public function enrolledClasses(): BelongsToMany
    {
        // NOTE: Make sure the keys 'user_id' and 'class_id' match your pivot table columns.
        return $this->belongsToMany(ClassSection::class, 'class_student', 'user_id', 'class_id');
    }

    // REMOVED: The old 'courses' and 'taughtCourses' methods have been removed to avoid confusion.
}