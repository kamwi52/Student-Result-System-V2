
<?php

namespace App\Exports;

use App\Models\ClassSection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class EnrolledStudentsExport implements FromQuery, WithHeadings, WithMapping
{
    public function __construct(protected ClassSection $classSection) {}

    public function query()
    {
        return $this->classSection->students()
            ->orderBy('name')
            ->with('profile'); // Assuming profile relationship exists
    }

    public function headings(): array
    {
        return [
            'ID',
            'Name', 
            'Email',
            'Enrollment Date',
            'Status'
        ];
    }

    public function map($student): array
    {
        return [
            $student->id,
            $student->name,
            $student->email,
            $student->pivot->enrolled_at,
            $student->pivot->status
        ];
    }
}