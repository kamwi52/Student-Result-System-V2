// App\Models\Student.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Student extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'class_section_id', 'user_id']; // Assuming you link to users

    public function classSection(): BelongsTo
    {
        return $this->belongsTo(ClassSection::class, 'class_section_id');
    }

    public function user(): BelongsTo //Assuming students are also users
    {
        return $this->belongsTo(User::class); // By default, Laravel will look for a 'user_id' column
    }
}