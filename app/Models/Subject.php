<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'code'];

    /**
     * The assessments that belong to the subject.
     */
    public function assessments(): HasMany
    {
        return $this->hasMany(Assessment::class);
    }
    
    /**
     * The classes that teach this subject.
     */
    public function classes(): BelongsToMany
    {
        return $this->belongsToMany(ClassSection::class, 'class_section_subject');
    }

    /**
     * === NEW: Get all assignments for this subject. ===
     */
    public function assignments(): HasMany
    {
        return $this->hasMany(Assignment::class);
    }
}