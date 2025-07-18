<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany; // Make sure this is present
use Illuminate\Database\Eloquent\Relations\HasMany;

class Subject extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['name', 'code']; // Assuming 'code' is also a fillable field for subjects

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
     */
    public function qualifiedTeachers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'subject_user', 'subject_id', 'user_id');
    }
}