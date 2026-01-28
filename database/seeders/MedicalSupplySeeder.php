<?php

namespace Database\Seeders;

use App\Models\MedicalSupply;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class MedicalSupplySeeder extends Seeder
{
    public function run(): void
    {
        $supplies = [
            ['name' => 'Branular yellow gauge 24', 'unit_of_measure' => 'pcs', 'category' => 'Cannulation'],
            ['name' => 'Branular blue gauge 22', 'unit_of_measure' => 'pcs', 'category' => 'Cannulation'],
            ['name' => 'Branular pink gauge 20', 'unit_of_measure' => 'pcs', 'category' => 'Cannulation'],

            ['name' => 'Blue monofil', 'unit_of_measure' => 'pkts', 'category' => 'Sutures'],

            ['name' => 'Crepe bandage 6 inch', 'unit_of_measure' => 'pcs', 'category' => 'Bandages'],
            ['name' => 'Crepe bandage 4 inch', 'unit_of_measure' => 'pcs', 'category' => 'Bandages'],
            ['name' => 'Crepe bandage 2 inch', 'unit_of_measure' => 'pcs', 'category' => 'Bandages'],

            ['name' => 'Cotton wool 400g', 'unit_of_measure' => 'pcs', 'category' => 'Dressings'],

            ['name' => 'Gauze roll 1.5kg', 'unit_of_measure' => 'pcs', 'category' => 'Dressings'],
            ['name' => 'Gauze bandage 6 inch', 'unit_of_measure' => 'dozens', 'category' => 'Dressings'],
            ['name' => 'Gauze bandage 4 inch', 'unit_of_measure' => 'dozens', 'category' => 'Dressings'],
            ['name' => 'Sterile gauze swabs 100s', 'unit_of_measure' => 'pkts', 'category' => 'Dressings'],

            ['name' => 'Infusion giving sets', 'unit_of_measure' => 'pcs', 'category' => 'IV Supplies'],

            ['name' => 'ORS sachets', 'unit_of_measure' => 'pcs', 'category' => 'Rehydration'],

            ['name' => 'Spirit (methylated)', 'unit_of_measure' => 'litres', 'category' => 'Disinfectants'],

            ['name' => 'Water for injection', 'unit_of_measure' => 'ampoules', 'category' => 'IV Supplies'],
        ];

        foreach ($supplies as $supply) {
            MedicalSupply::updateOrCreate(
                ['name' => $supply['name']],
                [
                    'id' => (string) Str::uuid(), 
                    'unit_of_measure' => $supply['unit_of_measure'],
                    'category' => $supply['category'],
                    'is_active' => true,
                ]
            );
        }

         
    }
}
