<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Year;

class YearSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Year::create([
            'name' => 'الاول الثانوي'
        ]);

        Year::create([
            'name' => 'الثاني الاعدادي'
        ]);
    }
}
