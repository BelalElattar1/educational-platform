<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Section;

class SectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Section::create([
            'name'        => 'امتحان عالدعامة',
            'time'        => 60,
            'type'        => 'exam',
            'exam_mark'   => 20,
            'category_id' => 1
        ]);

        Section::create([
            'name'        => 'امتحان عالدعامة',
            'link'        => '<iframe width="560" height="315" src="https://www.youtube.com/embed/SjdlJx9cmx8?si=vj70p-ZfSAncuuZS" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>',
            'type'        => 'video',
            'category_id' => 2
        ]);

        Section::create([
            'name'        => 'امتحان عالدعامة',
            'link'        => 'https://api.mohammed-ayman.com/books/pdX1cJKu8n24DsR9wwzMuzN3VlwyJIy0yvrrHIYC.pdf',
            'type'        => 'pdf',
            'category_id' => 2
        ]);
    }
}
