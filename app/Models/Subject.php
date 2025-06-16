<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    // ADD 'user_id' TO THIS ARRAY
    protected $fillable = [
        'name',
        'code',
        'description',
        'user_id', // <-- THIS IS THE REQUIRED CHANGE
    ];

    // This is the relationship method you added earlier, which is also correct
    public function teacher()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // This is the other relationship method, also correct
    public function students()
    {
        return $this->belongsToMany(User::class);
    }
    public function classSections() { return $this->hasMany(ClassSection::class); }
}