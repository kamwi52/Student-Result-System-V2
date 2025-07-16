<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany; // Import HasMany

class ClassSection extends Model
{
    use HasFactory;

    protected $table = 'class_sections';

    // REMOVED: teacher_id is no longer on this table.
    protected $fillable = [
        'name',
        'academic_session_id',
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
     * === NEW: Get all assignments for this class. ===
     */
    public function assignments(): HasMany
    {
        return $this->hasMany(Assignment::class);
    }
}