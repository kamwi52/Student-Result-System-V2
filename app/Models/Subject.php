<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Subject extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    // === UPDATED: Added 'description' to make sure all form fields can be saved ===
    protected $fillable = ['name', 'code', 'description'];

    /**
     * The assignments that belong to this subject.
     */
    public function assignments(): HasMany
    {
        return $this->hasMany(Assignment::class);
    }
    
    /**
     * The classes that teach this subject.
     */
    public function classSections(): BelongsToMany
    {
        return $this->belongsToMany(ClassSection::class, 'class_section_subject');
    }

    /**
     * Get the teachers who are qualified to teach this subject.
     * This links to the 'subject_user' pivot table.
     *
     * === UPDATED: Renamed from 'qualifiedTeachers' to 'teachers' ===
     * This name now matches the logic in the SubjectController (`withCount('teachers')`)
     */
    public function teachers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'subject_user', 'subject_id', 'user_id');
    }
}