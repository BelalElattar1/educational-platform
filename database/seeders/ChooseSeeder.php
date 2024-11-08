<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Choose;

class ChooseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Choose::create([
            'name' => 'هو عبارة عن مياه',
            'status' => 1,
            'question_id' => 1
        ]);

        Choose::create([
            'name' => 'هو عبارة عن حجر',
            'status' => 0,
            'question_id' => 1
        ]);

        Choose::create([
            'name' => 'من الحنفية',
            'status' => 0,
            'question_id' => 2
        ]);

        Choose::create([
            'name' => 'من السرير',
            'status' => 1,
            'question_id' => 2
        ]);
    }
}
