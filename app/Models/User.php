<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany; // <-- ADD THIS if not present
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\ClassSection; // <-- ADD THIS if not present
use App\Models\Result; // <-- ADD THIS

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
        'role', // Make sure 'role' is in fillable if you are using User::create
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
     * Get the classes taught by the user (if they are a teacher).
     */
    public function classes(): HasMany
    {
        return $this->hasMany(ClassSection::class, 'teacher_id');
    }

    /**
     * Get all of the results for the User (as a student).
     */
    public function results(): HasMany
    {
        return $this->hasMany(Result::class);
    }
}