<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
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
    // In User.php
public function courses()
{
    return $this->belongsToMany(\App\Models\Course::class);
}
// Add this method to the User model
public function taughtCourses()
{
    return $this->hasMany(Course::class);
}
public function taughtClasses()
{
    // The foreign key on the 'classes' table is 'user_id'
    // This tells Laravel that this User has many ClassSections.
    return $this->hasMany(\App\Models\ClassSection::class, 'user_id');
}
public function enrolledClasses() { return $this->belongsToMany(\App\Models\ClassSection::class, 'class_student', 'user_id', 'class_id'); }
}
