<?php

namespace App\Models;

// === FIX: Corrected namespace separators from -> to \ ===
use Illuminate\Database\Eloquent\Factories\HasFactory; // Corrected line
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
// === END FIX ===

class ClassSection extends Model
{
    use HasFactory; // Use the trait

    protected $table = 'class_sections'; // Assuming your table is named 'class_sections'

    protected $fillable = [
        'name',
        'academic_session_id',
        'grading_scale_id',
    ];

    /**
     * Get all the teacher assignments for this class.
     */
    public function assignments(): HasMany
    {
        return $this->hasMany(Assignment::class);
    }

    /**
     * Get the "homeroom" or general teachers for this class.
     */
    public function teachers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'class_section_teacher');
    }

    /**
     * The subjects that belong to the class.
     * This method needs to use the custom pivot model!
     */
    public function subjects(): BelongsToMany
    {
        // Make sure ClassSubject model is imported at the top if used with ->using()
        return $this->belongsToMany(Subject::class, 'class_section_subject')
                    ->withPivot('teacher_id') // Ensure teacher_id is loaded from the pivot table
                    ->withTimestamps()
                    ->using(ClassSubject::class); // <-- TELL LARAVEL TO USE YOUR CUSTOM PIVOT MODEL
    }

    /**
     * Get the students enrolled in this class.
     */
    public function students(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'enrollments', 'class_section_id', 'user_id');
    }

    /**
     * Get the academic session this class belongs to.
     */
    public function academicSession(): BelongsTo
    {
        return $this->belongsTo(AcademicSession::class);
    }

    /**
     * Get the grading scale used by this class.
     */
    public function gradingScale(): BelongsTo
    {
        return $this->belongsTo(GradingScale::class);
    }
}