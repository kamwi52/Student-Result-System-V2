<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

// --- ADD THIS LINE. It's needed for the new relationship. ---
use App\Models\Enrollment;

class ClassSection extends Model
{
    use HasFactory;

    protected $table = 'class_sections';

    protected $fillable = [
        'name',
        'academic_session_id',
        'grading_scale_id',
    ];

    public function assignments(): HasMany
    {
        return $this->hasMany(Assignment::class);
    }

    public function teachers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'class_section_teacher');
    }

    public function subjects(): BelongsToMany
    {
        return $this->belongsToMany(Subject::class, 'class_section_subject')
                    ->withPivot('teacher_id')
                    ->withTimestamps()
                    ->using(ClassSubject::class);
    }

    public function students(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'enrollments', 'class_section_id', 'user_id');
    }

    public function academicSession(): BelongsTo
    {
        return $this->belongsTo(AcademicSession::class);
    }

    public function gradingScale(): BelongsTo
    {
        return $this->belongsTo(GradingScale::class);
    }
    
    // =========================================================================
    // === THE DEFINITIVE FIX: THE MISSING RELATIONSHIP IS NOW HERE ============
    // This provides the direct bridge to the enrollment records.
    // =========================================================================
    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class);
    }

    /**
     * This method gets all unique teachers assigned to this class's subjects.
     */
    public function getSubjectTeachersAttribute()
    {
        $teacherIds = $this->subjects()->get()->pluck('pivot.teacher_id')->unique()->filter();
        return User::whereIn('id', $teacherIds)->get();
    }
}