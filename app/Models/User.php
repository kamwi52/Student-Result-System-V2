<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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
        'role',
        'student_id', // Assuming you have this for students
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
     * Get the results for the student.
     * A user (student) has many results.
     */
    public function results(): HasMany
    {
        return $this->hasMany(Result::class);
    }

    /**
     * The classes that a user (teacher) is assigned to teach.
     * This is the relationship that will fix the error.
     */
    public function classSections(): BelongsToMany
    {
        // Assumes a pivot table named 'class_section_user'
        return $this->belongsToMany(ClassSection::class, 'class_section_user');
    }

    /**
     * The enrollments for a user (student).
     * A student has many enrollments.
     */
    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class);
    }
}