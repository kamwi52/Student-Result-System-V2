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
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'subject_id',
        'class_id', // <-- 1. ADD class_id TO MAKE IT SAVEABLE
        'academic_session_id',
        'max_marks',
        'weightage',
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
     * =========================================================
     *  2. DEFINE THE NEW RELATIONSHIP TO THE CLASS
     * =========================================================
     * Get the class section that this assessment belongs to.
     */
    public function classSection(): BelongsTo
    {
        // This tells Laravel that an Assessment belongs to a ClassSection,
        // and the link is stored in the 'class_id' column.
        return $this->belongsTo(ClassSection::class, 'class_id');
    }
}