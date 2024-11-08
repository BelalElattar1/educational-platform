<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::create([
            'name'      => 'لمحاضرة الأولي - بتاريخ الأربعاء ٧ - ٨ بإذن الله',
            'title'     => 'محتوى المحضارة الدعامة في النبات كاملا',
            'course_id' => 1,
        ]);

        Category::create([
            'name'      => 'المحاضرة الثالثة للثانوية العامة والأزهرية 2025',
            'title'     => 'الغضاريف والمفاصل والاربطة والاوتار',
            'course_id' => 2,
        ]);
    }
}
