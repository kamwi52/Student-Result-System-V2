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
     * Therefore, 'class_id' should not be here.
     */
    protected $fillable = [
        'name',
        'subject_id',
        'academic_session_id',
        'max_marks',
        'weightage',
        'assessment_date', // Added for correctness
    ];

    /**
     * Get the subject that this assessment belongs to.
     * This is the crucial relationship for the dropdown to work.
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