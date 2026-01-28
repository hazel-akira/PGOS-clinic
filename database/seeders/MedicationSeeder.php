<?php

namespace Database\Seeders;

use App\Models\Medication;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
class MedicationSeeder extends Seeder
{
    public function run(): void
    {
        $medications = [
            [
                'name' => 'Adrenaline injection 1ml',
                'dosage_form' => 'Ampoules',
                'strength' => '1ml',
                'category' => 'Emergency',
                'requires_prescription' => true,
            ],
            [
                'name' => 'Albendazole tablets',
                'dosage_form' => 'Doses',
                'category' => 'Antiparasitic',
                'requires_prescription' => true,
            ],
            [
                'name' => 'Amoxil capsules',
                'dosage_form' => 'Pkts',
                'strength' => '250mg',
                'category' => 'Antibiotic',
                'requires_prescription' => true,
            ],
            [
                'name' => 'Amoxiclav',
                'dosage_form' => 'Pkts',
                'strength' => '375mg',
                'category' => 'Antibiotic',
                'requires_prescription' => true,
            ],
            [
                'name' => 'Ascoril syrup',
                'dosage_form' => 'Bottles',
                'strength' => '100ml',
                'category' => 'Cough & Cold',
            ],
            [
                'name' => 'Brufen tablets',
                'dosage_form' => 'Pkts',
                'strength' => '400mg',
                'category' => 'Pain Relief',
            ],
            [
                'name' => 'Cetirizine tablets',
                'dosage_form' => 'Pkts',
                'strength' => '10mg',
                'category' => 'Antihistamine',
            ],
            [
                'name' => 'Ciprofloxacin tablets',
                'dosage_form' => 'Pkts',
                'strength' => '500mg',
                'category' => 'Antibiotic',
                'requires_prescription' => true,
            ],
            [
                'name' => 'Ciprofloxacin eye/ear drops',
                'dosage_form' => 'Doses',
                'category' => 'Antibiotic',
                'requires_prescription' => true,
            ],
            [
                'name' => 'Diclofenac gel',
                'dosage_form' => 'Tubes',
                'strength' => '20g',
                'category' => 'Pain Relief',
            ],
            [
                'name' => 'Hydrocortisone injection',
                'dosage_form' => 'Vials',
                'strength' => '100mg',
                'category' => 'Steroid',
                'requires_prescription' => true,
            ],
            [
                'name' => 'Metronidazole tablets',
                'dosage_form' => 'Pkts',
                'strength' => '400mg',
                'category' => 'Antibiotic',
                'requires_prescription' => true,
            ],
            [
                'name' => 'Normal Saline',
                'dosage_form' => 'Bottles',
                'strength' => '500ml',
                'category' => 'IV Fluids',
            ],
            [
                'name' => 'Ventolin inhaler',
                'dosage_form' => 'Inhaler',
                'strength' => '100 micrograms',
                'category' => 'Respiratory',
            ],
            [
                'name' => 'Vitamin K',
                'dosage_form' => 'Ampoules',
                'category' => 'Injection',
                'requires_prescription' => true,
            ],
        ];

        foreach ($medications as $medication) {
            Medication::updateOrCreate(
                ['name' => $medication['name']],
                array_merge(
                    [
                        'id' => (string) Str::uuid(),   // <-- force UUID here
                        'generic_name' => null,
                        'manufacturer' => null,
                        'description' => null,
                        'dosage_instructions' => null,
                        'is_controlled_substance' => false,
                        'is_active' => true,
                        'created_by' => null,
                        'updated_by' => null,
                    ],
                    $medication
                )
            );
        }

    }
}
