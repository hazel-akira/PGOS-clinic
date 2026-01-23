<?php

namespace Database\Seeders;

use App\Models\Person;
use App\Models\Guardian;
use App\Models\GuardianLink;
use App\Models\User;
use Illuminate\Database\Seeder;

class ParentStudentLinkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the parent user
        $parentUser = User::where('email', 'parent@schoolclinic.com')->first();
        
        if (!$parentUser || !$parentUser->guardian) {
            $this->command->warn('Parent user or guardian not found. Please run UserSeeder first.');
            return;
        }

        $guardian = $parentUser->guardian;

        // Find or create a sample student (person with person_type = 'STUDENT')
        $student = Person::where('person_type', 'STUDENT')->first();

        if (!$student) {
            // Create a sample student if none exists
            $student = Person::create([
                'person_type' => 'STUDENT',
                'adm_or_staff_no' => 'STU001',
                'first_name' => 'Jane',
                'last_name' => 'Student',
                'gender' => 'FEMALE',
                'dob' => now()->subYears(10),
                'phone' => '+254712345679',
                'email' => 'jane.student@school.com',
                'status' => 'ACTIVE',
            ]);

            $this->command->info('Created sample student: ' . $student->full_name);
        }

        // Link parent to student
        $link = GuardianLink::firstOrCreate(
            [
                'student_person_id' => $student->id,
                'guardian_id' => $guardian->id,
            ],
            [
                'is_primary' => true,
                'notes' => 'Primary guardian link created via seeder',
            ]
        );

        $this->command->info('Linked parent "' . $guardian->full_name . '" to student "' . $student->full_name . '"');
        $this->command->info('Parent can now access the parent dashboard at /parent');
    }
}
