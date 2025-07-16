<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GradingScale extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['name'];

    /**
     * A Grading Scale has many individual Grade definitions (e.g., A, B, C).
     */
    public function grades(): HasMany
    {
        return $this->hasMany(Grade::class)->orderBy('min_score', 'desc');
    }
}