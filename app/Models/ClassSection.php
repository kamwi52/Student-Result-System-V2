<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\ClassSubject;

class ClassSection extends Model
{
    use HasFactory;

    protected $table = 'class_sections';

    protected $fillable = [
        'name',
        'academic_session_id',
        'grading_scale_id',
    ];

    /**
     * Get all the teacher assignments for this class.
     * This is separate from the subject-teacher link. It might be used for linking assessments to a class.
     */
    public function assignments(): HasMany
    {
        return $this->hasMany(Assignment::class);
    }

    /**
     * Get the "homeroom" or general teachers for this class.
     * Note: This is now separate from subject-specific teachers.
     */
    public function teachers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'class_section_teacher');
    }

    /**
     * === THIS IS THE CRITICAL UPDATE ===
     * The subjects that belong to the class.
     * We've added withPivot('teacher_id') to access the teacher assigned to each subject within this class.
     */
    public function subjects(): BelongsToMany
{
    return $this->belongsToMany(Subject::class, 'class_section_subject')
                ->withPivot('teacher_id')
                ->withTimestamps()
                ->using(ClassSubject::class); // <-- ADD THIS LINE
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