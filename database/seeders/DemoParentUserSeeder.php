<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Patient;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class DemoParentUserSeeder extends Seeder
{
    /**
     * Seed demo parent users for testing.
     * 
     * IMPORTANT: Only run this in development/testing environments!
     */
    public function run(): void
    {
        if (app()->environment('production')) {
            $this->command->error('Cannot run demo seeder in production!');
            return;
        }

        // Ensure parent role exists
        $parentRole = Role::firstOrCreate(
            ['name' => 'parent'],
            ['guard_name' => 'web']
        );

        // Demo Parent 1
        $parent1 = User::firstOrCreate([
            'email' => 'parent1@demo.com',
        ], [
            'name' => 'John Doe (Parent)',
            'password' => Hash::make('password123'),
        ]);
        
        if (!$parent1->hasRole('parent')) {
            $parent1->assignRole('parent');
        }

        // Demo Parent 2
        $parent2 = User::firstOrCreate([
            'email' => 'parent2@demo.com',
        ], [
            'name' => 'Jane Smith (Parent)',
            'password' => Hash::make('password123'),
        ]);
        
        if (!$parent2->hasRole('parent')) {
            $parent2->assignRole('parent');
        }

        // Link existing students to parents (if they exist)
        // Update guardian_email for demo students
        $students = Patient::where('type', 'student')
            ->where('is_active', true)
            ->take(3)
            ->get();

        if ($students->count() >= 2) {
            // Assign first 2 students to parent1
            $students[0]->update([
                'guardian_email' => 'parent1@demo.com',
                'guardian_name' => 'John Doe',
                'guardian_relationship' => 'Father',
            ]);
            
            if (isset($students[1])) {
                $students[1]->update([
                    'guardian_email' => 'parent1@demo.com',
                    'guardian_name' => 'John Doe',
                    'guardian_relationship' => 'Father',
                ]);
            }
            
            // Assign 3rd student to parent2 if exists
            if (isset($students[2])) {
                $students[2]->update([
                    'guardian_email' => 'parent2@demo.com',
                    'guardian_name' => 'Jane Smith',
                    'guardian_relationship' => 'Mother',
                ]);
            }

            $this->command->info('Demo parent users created and linked to students!');
            $this->command->info('Login credentials:');
            $this->command->info('  Email: parent1@demo.com | Password: password123');
            $this->command->info('  Email: parent2@demo.com | Password: password123');
        } else {
            $this->command->warn('No students found to link to parents. Create students first.');
        }
    }
}
