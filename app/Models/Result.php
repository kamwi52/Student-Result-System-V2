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
        'user_id',
        'class_id',
        'assessment_id',
        'score',
        'remarks',
    ];

    /**
     * Get the student (user) that owns the result.
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the class that this result belongs to.
     */
    public function classSection(): BelongsTo
    {
        return $this->belongsTo(ClassSection::class, 'class_id');
    }

    /**
     * Get the assessment that this result is for.
     */
    public function assessment(): BelongsTo
    {
        return $this->belongsTo(Assessment::class);
    }
}