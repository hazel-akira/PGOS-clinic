<?php

namespace Database\Seeders;

use App\Models\School;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SchoolSeeder extends Seeder
{
    public function run(): void
    {
        $schools = [
            ['name' => 'Pioneer School', 'code' => 'pioneer', 'website' => 'https://pioneerschools.ac.ke'],
            ['name' => 'Pioneer Girls School', 'code' => 'pioneer-girls', 'website' => 'https://pioneergirlsschool.co.ke'],
            ['name' => 'Pioneer Girls Junior Academy', 'code' => 'pioneer-girls-junior', 'website' => 'https://pioneergirlsjunioracademy.co.ke'],
            ['name' => 'Pioneer Junior Academy', 'code' => 'pioneer-junior', 'website' => 'https://pioneerjunioracademy.co.ke'],
            ['name' => 'St Paul Thomas Academy', 'code' => 'st-paul-thomas', 'website' => 'https://stpaulthomasacademy.co.ke'],
        ];

        foreach ($schools as $school) {
            School::updateOrCreate(
                ['code' => $school['code']],
                [
                    'id' => (string) Str::uuid(),
                    'name' => $school['name'],
                    'website' => $school['website'],
                    'active' => true,
                ]
            );
        }
    }
}
