<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory; // <-- Add this trait
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany; // <-- Add this import

class AcademicSession extends Model
{
    use HasFactory; // <-- Use the factory trait

    /**
     * The attributes that are mass assignable.
     * These fields can be filled using mass assignment (e.g., AcademicSession::create($data)).
     */
    protected $fillable = [
        'name',
        'start_date',
        'end_date',
        'is_current',
    ];

    /**
     * The attributes that should be cast to native types.
     * This is CRUCIAL for automatically converting date strings from the database into Carbon objects.
     */
    protected $casts = [
        'start_date' => 'date', // Casts to Carbon instance
        'end_date' => 'date',   // Casts to Carbon instance
        'is_current' => 'boolean', // Casts to boolean
    ];

    /**
     * Get the classes that belong to this academic session.
     * An AcademicSession can have many ClassSections.
     */
    public function classSections(): HasMany
    {
        return $this->hasMany(ClassSection::class);
    }

    /**
     * Get the assignments that belong to this academic session.
     * An AcademicSession can have many Assignments (if assignments are directly linked to sessions).
     */
    public function assignments(): HasMany
    {
        // Assuming your Assignment model also has 'academic_session_id' as a foreign key.
        return $this->hasMany(Assignment::class);
    }
}