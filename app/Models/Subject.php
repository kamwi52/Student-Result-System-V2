<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Subject extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['name', 'code', 'description'];

    /**
     * The classes that this subject is taught in.
     */
    public function classSections(): BelongsToMany
    {
        // This tells Laravel to use our new pivot table.
        return $this->belongsToMany(ClassSection::class, 'class_section_subject');
    }
}