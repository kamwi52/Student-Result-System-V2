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
     * The students enrolled in this class.
     */
    public function students(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'enrollments');
    }
}