<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Admin;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Clear permission cache
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // First, ensure permissions exist by creating them if they don't
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

        $actions = ['view', 'create', 'update', 'delete'];

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

        // Find the admin with email admin@noce.com
        $admin = Admin::where('email', 'admin@noce.com')->first();

        if ($admin) {
            // Get all permissions for the admin guard
            $allPermissions = Permission::where('guard_name', 'admin')->pluck('name')->toArray();

            if (!empty($allPermissions)) {
                // Assign all permissions to the admin
                $admin->syncPermissions($allPermissions);

                // Clear cache again after assigning permissions
                app()[PermissionRegistrar::class]->forgetCachedPermissions();
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Clear permission cache
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Find the admin with email admin@noce.com
        $admin = Admin::where('email', 'admin@noce.com')->first();

        if ($admin) {
            // Remove all permissions from the admin
            $admin->syncPermissions([]);

            // Clear cache again after removing permissions
            app()[PermissionRegistrar::class]->forgetCachedPermissions();
        }
    }
};
