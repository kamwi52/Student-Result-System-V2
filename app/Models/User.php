<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

// Import the correct models
use App\Models\Result;
use App\Models\Enrollment;
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
        'role',
        'email_verified_at',
        'student_id',
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
     */
    public function results(): HasMany
    {
        return $this->hasMany(Result::class, 'student_id');
    }
    
    /**
     * Get the classes that a user (as a teacher) is assigned to.
     */
    public function classes(): HasMany
    {
        return $this->hasMany(ClassSection::class, 'teacher_id');
    }

    /**
     * === FIX FOR "Enter Grades" BUTTON ===
     * An alias relationship to support older controllers that call classSections().
     * This forwards the call to the correct 'classes()' method.
     */
    public function classSections(): HasMany
    {
        return $this->classes();
    }

    /**
     * The enrollments for a user (student).
     */
    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class, 'student_id');
    }
}