<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Governorate;

class GovernorateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $governorates = [
            'القاهرة',
            'الجيزة',
            'الإسكندرية',
            'بورسعيد',
            'الإسماعيلية',
            'السويس',
            'قناة السويس',
            'شمال سيناء',
            'جنوب سيناء',
            'البحر الاحمر',
            'مطروح',
            'الفيوم',
            'البحيرة',
            'الغربية',
            'المنوفية',
            'القليوبية',
            'الدقهلية',
            'الشرقية',
            'اسيوط',
            'سوهاج',
            'قنا',
            'الاقصر',
            'اسوان',
            'بني سويف',
            'المنيا',
            'الوادي الجديد',
            'كفر الشيخ'
        ];

        foreach($governorates as $governorate) {

            Governorate::create([
                'name' => $governorate
            ]);

        }
    }
}
