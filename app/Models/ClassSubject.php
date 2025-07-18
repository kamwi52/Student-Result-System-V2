<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClassSubject extends Pivot
{
    // The table associated with the model.
    protected $table = 'class_section_subject';

    /**
     * Get the teacher assigned to this specific class-subject pairing.
     */
    public function teacher(): BelongsTo
    {
        // This pivot record belongs to one User (the teacher).
        return $this->belongsTo(User::class, 'teacher_id');
    }
}