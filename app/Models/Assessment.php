<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Assessment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     * An assessment is not tied to a single class, but to a subject.
     */
    protected $fillable = [
        'name',
        'subject_id',
        'academic_session_id',
        'max_marks',
        'weightage',
        'assessment_date',
    ];

    /**
     * An Assessment belongs to one Subject.
     * This method tells Laravel how to find that subject.
     */
    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    /**
     * Get the academic session that this assessment belongs to.
     */
    public function academicSession(): BelongsTo
    {
        return $this->belongsTo(AcademicSession::class);
    }

    /**
     * An assessment can have many results (one for each student).
     */
    public function results(): HasMany
    {
        return $this->hasMany(Result::class);
    }

    /**
     * An assessment can be included in many class assignments.
     */
    public function assignments(): HasMany
    {
        return $this->hasMany(Assignment::class);
    }
}