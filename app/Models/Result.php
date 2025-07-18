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
     */
    protected $fillable = [
        'user_id',            // The student's user ID
        'class_section_id',   // The class this result is for
        'assignment_id',      // The assignment (formerly assessment) this result is for
        'score',
        'remark',             // Singular 'remark'
    ];

    /**
     * Get the student (user) that owns the result.
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the class section that this result belongs to.
     */
    public function classSection(): BelongsTo
    {
        return $this->belongsTo(ClassSection::class);
    }

    /**
     * Get the assignment (formerly assessment) that this result is for.
     */
    public function assignment(): BelongsTo
    {
        return $this->belongsTo(Assignment::class); // Renamed from Assessment to Assignment
    }
}