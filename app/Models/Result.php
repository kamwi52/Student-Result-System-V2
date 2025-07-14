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
     * Note: 'class_id' has been removed as it's not a primary key for a result.
     * The connection to a class is through the student's enrollment.
     */
    protected $fillable = [
        'user_id',
        'student_id', // Keeping both for flexibility, user_id is the standard
        'assessment_id',
        'score',
        'remark', // Corrected from 'remarks' to be singular
    ];

    /**
     * Get the student (user) that owns the result.
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the assessment that this result is for.
     */
    public function assessment(): BelongsTo
    {
        return $this->belongsTo(Assessment::class);
    }
}