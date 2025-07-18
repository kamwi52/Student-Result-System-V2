<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Assignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'class_section_id', // <-- ADD THIS LINE
        'subject_id',
        'teacher_id',
        'academic_session_id',
        'max_marks',
        'weightage',
        'assessment_date',
    ];

    /**
     * An Assignment belongs to one ClassSection.
     */
    public function classSection(): BelongsTo
    {
        return $this->belongsTo(ClassSection::class);
    }

    /**
     * An Assignment belongs to one Subject.
     */
    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    /**
     * The teacher who owns this assignment.
     */
    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    /**
     * An assignment can have many results (one for each student).
     */
    public function results(): HasMany
    {
        return $this->hasMany(Result::class);
    }
    
    // If you also link AcademicSession directly to Assignment:
    public function academicSession(): BelongsTo
    {
        return $this->belongsTo(AcademicSession::class);
    }
}