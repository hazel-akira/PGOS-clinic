<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create roles if they don't exist (using existing RoleSeeder roles)
        $roles = [
            'clinic_nurse',
            'doctor',
            'admin',
            'principal_readonly',
            'parent',
        ];

        foreach ($roles as $roleName) {
            Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
        }

        // Create default admin user
        $admin = User::firstOrCreate(
            ['email' => 'admin@schoolclinic.com'],
            [
                'name' => 'System Administrator',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
        $admin->assignRole('admin');

        // Create clinic nurse user
        $nurse = User::firstOrCreate(
            ['email' => 'nurse@schoolclinic.com'],
            [
                'name' => 'Clinic Nurse',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
        $nurse->assignRole('clinic_nurse');

        // Create doctor user
        $doctor = User::firstOrCreate(
            ['email' => 'doctor@schoolclinic.com'],
            [
                'name' => 'Dr. Jane Smith',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
        $doctor->assignRole('doctor');

        // Create principal user (read-only)
        $principal = User::firstOrCreate(
            ['email' => 'principal@schoolclinic.com'],
            [
                'name' => 'Principal',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
        $principal->assignRole('principal_readonly');

        // Create parent user
        $parent = User::firstOrCreate(
            ['email' => 'parent@schoolclinic.com'],
            [
                'name' => 'John Parent',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
        $parent->assignRole('parent');

        // Create guardian record for parent
        $guardian = \App\Models\Guardian::firstOrCreate(
            ['user_id' => $parent->id],
            [
                'full_name' => 'John Parent',
                'phone' => '+254712345678',
                'email' => 'parent@schoolclinic.com',
                'relationship' => 'PARENT',
            ]
        );

        $this->command->info('Users created successfully!');
        $this->command->info('Default password for all users: password');
        $this->command->info('Admin: admin@schoolclinic.com');
        $this->command->info('Nurse: nurse@schoolclinic.com');
        $this->command->info('Doctor: doctor@schoolclinic.com');
        $this->command->info('Principal: principal@schoolclinic.com');
        $this->command->info('Parent: parent@schoolclinic.com');
    }
}
