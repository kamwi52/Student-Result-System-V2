<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany; // <-- ADD THIS if not present

class ClassSection extends Model
{
    use HasFactory;

    // Use the 'classes' table
    protected $table = 'classes';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'subject_id',
        'teacher_id',
        'academic_session_id',
        // Note: 'user_id' is nullable and not mass-assignable by default
    ];

    /**
     * The students that belong to the ClassSection.
     */
    public function students(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'class_student', 'class_id', 'user_id');
    }

    /**
     * Get the subject that this class belongs to.
     */
    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    /**
     * Get the teacher (user) that this class is assigned to.
     */
    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    /**
     * Get the academic session that this class belongs to.
     */
    public function academicSession(): BelongsTo
    {
        return $this->belongsTo(AcademicSession::class);
    }

    /**
     * Get all of the results for the ClassSection.
     */
    public function results(): HasMany
    {
        return $this->hasMany(Result::class, 'class_id');
    }
}