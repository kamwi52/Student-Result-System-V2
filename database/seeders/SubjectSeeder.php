<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Subject;

class SubjectSeeder extends Seeder
{
    public function run(): void
    {
        $subjects = [
            ['name' => 'Mathematics', 'code' => 'MATH'],
            ['name' => 'Information and Communication Technology', 'code' => 'INFO'],
            ['name' => 'Civic Education', 'code' => 'CIVI'],
            ['name' => 'English', 'code' => 'ENGL'],
            ['name' => 'Chemistry', 'code' => 'CHEM'],
            ['name' => 'Physics', 'code' => 'PHYS'],
            ['name' => 'DESIGN AND TECHNOLOGY', 'code' => 'DESI'],
            ['name' => 'Social Studies', 'code' => 'SOCI'],
            ['name' => 'Religious Education', 'code' => 'RELI'],
            ['name' => 'Business Studies', 'code' => 'BUSI'],
            ['name' => 'Computer Studies', 'code' => 'COMP'],
            ['name' => 'Intergrated Science', 'code' => 'INTE'],
            ['name' => 'Chitonga', 'code' => 'CHIT'],
            ['name' => 'Home Economics', 'code' => 'HOME'],
            ['name' => 'Music', 'code' => 'MUSI'],
            ['name' => 'Art and Design', 'code' => 'ART'],
            ['name' => 'Chemistry (Pure)', 'code' => 'CHEMP'],
            ['name' => 'Physics (Pure)', 'code' => 'PHYSP'],
            ['name' => 'Biology', 'code' => 'BIOL'],
            ['name' => 'Additional Mathematics', 'code' => 'ADMA'],
            ['name' => 'Design and Technology', 'code' => 'DTEC'],
            ['name' => 'Geography', 'code' => 'GEOG'],
            ['name' => 'Literature in English', 'code' => 'LITE'],
            ['name' => 'Food and Nutrition', 'code' => 'FOOD'],
            ['name' => 'Principles of Accounts', 'code' => 'PRIN'],
            ['name' => 'Commerce', 'code' => 'COMM'],
            ['name' => 'History', 'code' => 'HIST'],
            ['name' => 'Travel and Tourism', 'code' => 'TRAV'],
            ['name' => 'French', 'code' => 'FREN'],
            ['name' => 'Agricultural Science', 'code' => 'AGRI'],
            ['name' => 'Metal Work', 'code' => 'META'],
            ['name' => 'Wood Work', 'code' => 'WOOD'],
            ['name' => 'Geometrical and Mechanical Drawing', 'code' => 'GEOM'],
            ['name' => 'Office Practice', 'code' => 'OFFI'],
            ['name' => 'Shorthand', 'code' => 'SHOR'],
            ['name' => 'Typewriting', 'code' => 'TYPE'],
            ['name' => 'Fashion and Fabrics', 'code' => 'FASH'],
        ];

        foreach ($subjects as $subject) {
            Subject::updateOrCreate(['name' => $subject['name']], $subject);
        }
    }
}