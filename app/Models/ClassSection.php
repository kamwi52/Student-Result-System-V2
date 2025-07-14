<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClassSection extends Model
{
    use HasFactory;

    protected $table = 'class_sections';

    protected $fillable = [
        'name',
        'academic_session_id',
        'teacher_id',
        'grading_scale_id',
    ];

    /**
     * The subjects taught in this class (many-to-many).
     */
    public function subjects(): BelongsToMany
    {
        return $this->belongsToMany(Subject::class, 'class_section_subject');
    }
    
    /**
     * A class section may have a primary subject.
     */
    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }
    
    /**
     * The primary relationship to get students enrolled in this class.
     */
    public function students(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            'enrollments',
            'class_section_id',
            'user_id'
        );
    }

    /**
     * Get all assessments associated with this class through its subjects AND academic session.
     */
    public function getAssessments()
    {
        // This needs the Assessment model to be imported.
        // Adding the use statement at the top of the file would be best practice.
        $subjectIds = $this->subjects()->pluck('subjects.id');

        if ($subjectIds->isEmpty()) {
            return collect();
        }

        return Assessment::with('subject')
            ->whereIn('subject_id', $subjectIds)
            ->where('academic_session_id', $this->academic_session_id)
            ->get()
            ->map(function ($assessment) {
                $assessment->display_name = "{$assessment->subject->name} - {$assessment->name} (Max: {$assessment->max_marks})";
                return $assessment;
            })
            ->sortBy('display_name');
    }

    /**
     * The academic session this class belongs to.
     */
    public function academicSession(): BelongsTo
    {
        return $this->belongsTo(AcademicSession::class);
    }

    /**
     * The teacher assigned to this class.
     */
    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    /**
     * === THE FIX ===
     * Alias for the students() relationship. This allows older controllers
     * that call enrollments() to work without needing to be refactored.
     */
    public function enrollments(): BelongsToMany
    {
        return $this->students();
    }
}