<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class Assessment extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'subject_id',
        'academic_session_id',
        'max_marks',
        'weightage',
        'assessment_date',
        'class_section_id',
    ];

    protected $casts = [
        'assessment_date' => 'datetime',
    ];

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    public function academicSession(): BelongsTo
    {
        return $this->belongsTo(AcademicSession::class);
    }

    public function classSection(): BelongsTo
    {
        return $this->belongsTo(ClassSection::class);
    }

    public function assignment(): HasOne
    {
        return $this->hasOne(Assignment::class);
    }

    public function results(): HasMany
    {
        return $this->hasMany(Result::class);
    }

    /**
     * Get the term for the assessment through the assignment.
     */
    public function term(): HasOneThrough
    {
        return $this->hasOneThrough(
            Term::class,           // The final model we want to access
            Assignment::class,     // The intermediate model
            'assessment_id',       // Foreign key on the assignments table (links to Assessment)
            'id',                  // Foreign key on the terms table (links to Term)
            'id',                  // Local key on the assessments table
            'term_id'              // Local key on the assignments table (links to Term)
        );
    }
}