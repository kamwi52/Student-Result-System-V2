<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Term extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
    ];

    /**
     * Get all of the assessments for the term through the assignments.
     * This defines a "has many through" relationship.
     * Term -> has many -> Assignments -> has one -> Assessment
     */
    public function assessments(): HasManyThrough
    {
        return $this->hasManyThrough(Assessment::class, Assignment::class);
    }
}