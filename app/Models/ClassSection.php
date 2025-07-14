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
     * The subjects taught in this class.
     */
    public function subjects(): BelongsToMany
    {
        return $this->belongsToMany(Subject::class, 'class_section_subject');
    }
    
    /**
     * The students enrolled in this class.
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
        // Get all subject IDs associated with this class from the pivot table
        $subjectIds = $this->subjects()->pluck('subjects.id');

        // If this class teaches no subjects, there can be no assessments.
        if ($subjectIds->isEmpty()) {
            return collect();
        }

        // --- THE FIX ---
        // Return a collection of assessments that belong to the class's subjects
        // AND ALSO belong to the same academic session as the class itself.
        return Assessment::with('subject')
            ->whereIn('subject_id', $subjectIds)
            ->where('academic_session_id', $this->academic_session_id) // This is the crucial missing line.
            ->get()
            ->map(function ($assessment) {
                // Create a user-friendly display name for the dropdown
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
}