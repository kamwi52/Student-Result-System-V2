<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
     * === THE FIX: ADD THIS MISSING RELATIONSHIP ===
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
}