<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        
        // User::factory()->create([
            //     'name' => 'Test User',
            //     'email' => 'test@example.com',
            // ]);
            
        $this->call([
            GovernorateSeeder::class,
            YearSeeder::class,
            DivisionSeeder::class,
            UserSeeder::class,
            CourseSeeder::class,
            CategorySeeder::class,
            SectionSeeder::class,
            QuestionSeeder::class,
            ChooseSeeder::class,
            CodeSeeder::class,
        ]);

        // User::factory(50)->create();
        
    }
}
