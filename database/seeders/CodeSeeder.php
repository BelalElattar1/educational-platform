<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Code;

class CodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Code::Create([
            'code' => uniqid(),
            'price' => 120,
            'is_active' => 1
        ]); 

        Code::Create([
            'code' => uniqid(),
            'price' => 250,
            'is_active' => 1
        ]); 
    }
}
