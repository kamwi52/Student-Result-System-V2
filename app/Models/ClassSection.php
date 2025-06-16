<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassSection extends Model {
    use HasFactory;
    protected $table = 'classes'; // Tell Laravel the table name is 'classes'
    protected $fillable = ['name', 'subject_id', 'user_id', 'academic_session_id'];

    public function subject() { return $this->belongsTo(Subject::class); }
    public function teacher() { return $this->belongsTo(User::class, 'user_id'); }
    public function academicSession() { return $this->belongsTo(AcademicSession::class); }
    public function students() { return $this->belongsToMany(User::class, 'class_student', 'class_id', 'user_id'); }
}