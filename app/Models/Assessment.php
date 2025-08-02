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
     * === FIX #1: Added 'term_id' to the fillable array ===
     * This is required to allow mass-assignment of the term.
     */
    protected $fillable = [
        'name',
        'subject_id',
        'academic_session_id',
        'class_section_id',
        'term_id', // <-- ADDED
        'max_marks',
        'weightage',
        'assessment_date',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'assessment_date' => 'datetime',
    ];

    /**
     * Get the subject that this assessment belongs to.
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
     * Get the class section that this assessment is for.
     */
    public function classSection(): BelongsTo
    {
        return $this->belongsTo(ClassSection::class);
    }

    /**
     * Get all of the results for the Assessment.
     */
    public function results(): HasMany
    {
        return $this->hasMany(Result::class);
    }

    /**
     * === FIX #2: Replaced the incorrect relationship with a direct one ===
     * An Assessment now belongs directly to a Term.
     */
    public function term(): BelongsTo
    {
        return $this->belongsTo(Term::class);
    }

    // NOTE: The 'assignment()' and 'hasOneThrough term()' methods have been removed as they are part of the old, incorrect architecture.
}