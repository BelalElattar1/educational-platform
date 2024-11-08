<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Course;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Course::create([
            'title'       => 'كورس الهرمونات كاملا',
            'image'       => 'cards/0vfGFjeIoACNnGobkUkCzQEpkZrpEPtkXHngVmkr.png',
            'price'       => 150,
            'description' => 'خمس محاضرات تشمل شرح الفصل كامل + الكراش كورس',
            'year_id'     => 1,
            'division_id' => 1
        ]);

        Course::create([
            'title'       => 'للأزهر -٠ كورس الدعامة والحركة + ورشة العمل',
            'image'       => 'cards/0vfGFjeIoACNnGobkUkCzQEpkZrpEPtkXHngVmkr.png',
            'price'       => 200,
            'description' => 'يشمل هذا الكورس 6 محاضرات + ورشة عمل على الفصل كاملاً ( مدة الكورس = 7 أسابيع )',
            'year_id'     => 2,
            'division_id' => 2
        ]);
    }
}
