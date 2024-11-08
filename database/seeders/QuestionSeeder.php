<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Question;

class QuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Question::Create([
            'name'    => 'ما هو البحر',
            'exam_id' => 1
        ]);

        Question::Create([
            'name'    => 'من اين نشرب',
            'exam_id' => 1
        ]);
    }
}
