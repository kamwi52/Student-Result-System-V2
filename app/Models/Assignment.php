<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
// We are now using HasMany instead of HasManyThrough
use Illuminate\Database\Eloquent\Relations\HasMany;

class Assignment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'title',
        'subject_id',
        'class_section_id',
        'teacher_id',
        'assessment_id',
    ];

    /**
     * An Assignment belongs to one Assessment.
     */
    public function assessment(): BelongsTo
    {
        return $this->belongsTo(Assessment::class);
    }

    /**
     * An Assignment belongs to one ClassSection.
     */
    public function classSection(): BelongsTo
    {
        return $this->belongsTo(ClassSection::class);
    }

    /**
     * An Assignment belongs to one Subject.
     */
    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    /**
     * The teacher who owns this assignment.
     */
    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    /**
     * AN ASSIGNMENT HAS MANY RESULTS.
     * This relationship works because both Assignments and Results are linked
     * via the `assessment_id`. We explicitly tell Laravel which columns to use.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function results(): HasMany
    {
        // This tells Laravel: "Get all Results where the result's assessment_id
        // matches this assignment's assessment_id."
        return $this->hasMany(Result::class, 'assessment_id', 'assessment_id');
    }
}