<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assessment extends Model {
    use HasFactory;
    protected $fillable = ['name', 'max_marks', 'weightage', 'academic_session_id'];
    public function academicSession() { return $this->belongsTo(AcademicSession::class); }
}