<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'first_name'           => 'belal',
            'last_name'            => 'elattar',
            'is_active'            => 0,
            'is_admin'             => 0,
            'gender'               => 'male',
            'card_photo'           => 'cards/0vfGFjeIoACNnGobkUkCzQEpkZrpEPtkXHngVmkr.png',
            'student_phone_number' => '01125018188',
            'parent_phone_number'  => '01125018189',
            'email'                => 'belal@gmail.com',
            'password'             =>  Hash::make('password'),
            'year_id'              => 2,
            'division_id'          => 2,
            'governorate_id'       => 2,
        ]);

        User::create([
            'first_name'           => 'yousef',
            'last_name'            => 'ahmed',
            'is_active'            => 1,
            'is_admin'             => 1,
            'gender'               => 'male',
            'card_photo'           => 'cards/0vfGFjeIoACNnGobkUkCzQEpkZrpEPtkXHngVmkr.png',
            'student_phone_number' => '01234567899',
            'parent_phone_number'  => '01234567898',
            'email'                => 'yousef@gmail.com',
            'password'             => Hash::make('password'),
            'year_id'              => 1,
            'division_id'          => 1,
            'governorate_id'       => 1,
        ]);
    }
}
