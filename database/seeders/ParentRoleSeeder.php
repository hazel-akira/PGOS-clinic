<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class ParentRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create parent role if it doesn't exist
        $parentRole = Role::firstOrCreate(
            ['name' => 'parent'],
            ['guard_name' => 'web']
        );

        // Define permissions for parents
        $permissions = [
            'view_children',
            'view_medical_visits',
            'view_medical_reports',
        ];

        foreach ($permissions as $permission) {
            $perm = Permission::firstOrCreate(
                ['name' => $permission],
                ['guard_name' => 'web']
            );
            
            // Assign permission to parent role
            if (!$parentRole->hasPermissionTo($perm)) {
                $parentRole->givePermissionTo($perm);
            }
        }

        $this->command->info('Parent role and permissions created successfully!');
    }
}
