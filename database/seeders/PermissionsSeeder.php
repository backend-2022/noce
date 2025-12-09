<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define modules and their actions
        $modules = [
            'cities',
            'services',
            'free-services',
            'admins',
            'backup',
            'settings',
            'activity-logs',
            'permissions',
        ];

        $actions = ['view'];

        // Create permissions for each module and action combination
        foreach ($modules as $module) {
            foreach ($actions as $action) {
                $permissionName = "{$module}.{$action}";

                // Check if permission already exists
                $permission = Permission::where('name', $permissionName)
                    ->where('guard_name', 'admin')
                    ->first();

                if (!$permission) {
                    Permission::create([
                        'name' => $permissionName,
                        'guard_name' => 'admin',
                    ]);
                }
            }
        }

        $this->command->info('Permissions seeded successfully!');
    }
}
