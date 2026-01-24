<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * Creates roles for the PGoS Clinic Management System:
     * - clinic_nurse: Can manage visits, triage, basic treatment
     * - doctor: Full access to medical records and treatment
     * - admin: System administration
     * - principal_readonly: Read-only access for compliance reporting
     */
    public function run(): void
    {
        // Create roles if they don't exist
        $roles = [
            'clinic_nurse',
            'doctor',
            'admin',
            'principal_readonly',
            'parent',
        ];

        foreach ($roles as $roleName) {
            Role::firstOrCreate(['name' => $roleName]);
        }

        $this->command->info('Roles created successfully: ' . implode(', ', $roles));
    }
}
