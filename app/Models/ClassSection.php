<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassSection extends Model 
{
    use HasFactory;

    // IMPORTANT: This tells Laravel that this model corresponds to the 'classes' table.
    protected $table = 'classes';

    // The fields that are allowed to be mass-assigned.
    // We use 'teacher_id' consistently.
    protected $fillable = ['name', 'subject_id', 'teacher_id', 'academic_session_id'];

    // --- RELATIONSHIPS ---

    public function subject() 
    {
        return $this->belongsTo(Subject::class); 
    }
    
    // A class is taught by one teacher (a User).
    // The foreign key on the 'classes' table is 'teacher_id'.
    public function teacher() 
    {
        return $this->belongsTo(User::class, 'teacher_id'); 
    }
    
    public function academicSession() 
    {
        return $this->belongsTo(AcademicSession::class); 
    }

    // A class has many students (Users) enrolled in it.
    public function students() 
    {
        return $this->belongsToMany(User::class, 'class_student', 'class_id', 'user_id'); 
    }
}