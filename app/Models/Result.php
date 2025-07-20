<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Result extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',          // Student's ID (foreign key to users table)
        'assessment_id',    // Foreign key to assessments table
        'score',
        'comments',         // Renamed from 'remark' to match controller's usage
        'teacher_id',       // Foreign key to users table (the teacher who recorded it)
        'class_section_id', // Foreign key to class_sections table
    ];

    /**
     * Get the student (user) that owns the result.
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the assessment that the result belongs to.
     * --- REMOVED THE SEPARATE `assignment()` METHOD AS RESULTS ARE TIED TO ASSESSMENTS ---
     * If you need to access the Assignment through the Result, you'd do it via:
     * $result->assessment->assignment
     */
    public function assessment(): BelongsTo
    {
        return $this->belongsTo(Assessment::class);
    }

    /**
     * Get the class section that the result belongs to.
     */
    public function classSection(): BelongsTo
    {
        return $this->belongsTo(ClassSection::class);
    }

    /**
     * Get the teacher who recorded this result.
     */
    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }
}