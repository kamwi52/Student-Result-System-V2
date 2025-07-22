<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    /**
     * === THIS IS THE NEW HELPER FUNCTION (ACCESSOR) ===
     *
     * This method gets all unique teachers assigned to this class's subjects.
     * When you call `$class->subject_teachers` in a view, this code runs automatically.
     */
    public function getSubjectTeachersAttribute()
    {
        // 1. Get all teacher IDs from the 'class_section_subject' pivot table for this class.
        // 2. Pluck just the 'teacher_id' column.
        // 3. Get only the unique IDs (so a teacher listed for 4 subjects only appears once).
        // 4. Filter out any null values (subjects with no teacher assigned).
        $teacherIds = $this->subjects()->get()->pluck('pivot.teacher_id')->unique()->filter();

        // 5. Fetch the full User models for those teacher IDs.
        return User::whereIn('id', $teacherIds)->get();
    }
}