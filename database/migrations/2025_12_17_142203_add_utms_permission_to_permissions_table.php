<?php

use Illuminate\Database\Migrations\Migration;
use App\Models\Admin;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

return new class extends Migration
{
    public function up(): void
    {
        // Clear permission cache
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Check if permission already exists
        $permission = Permission::where('name', 'utms.view')
            ->where('guard_name', 'admin')
            ->first();

        if (!$permission) {
            Permission::create([
                'name' => 'utms.view',
                'guard_name' => 'admin',
            ]);
        }

        // Assign the permission to admins who have all view permissions
        // This ensures super admins get the new permission automatically
        $admin = Admin::where('email', 'admin@noce.com')->first();

        if ($admin) {
            // Get all view permissions including the new one
            $allViewPermissions = Permission::where('guard_name', 'admin')
                ->where('name', 'like', '%.view')
                ->pluck('name')
                ->toArray();

            if (!empty($allViewPermissions)) {
                $admin->syncPermissions($allViewPermissions);
            }
        }

        // Clear cache again after creating permission
        app()[PermissionRegistrar::class]->forgetCachedPermissions();
    }

    public function down(): void
    {
        // Clear permission cache
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Find and delete the permission
        $permission = Permission::where('name', 'utms.view')
            ->where('guard_name', 'admin')
            ->first();

        if ($permission) {
            $permission->delete();
        }

        // Clear cache again after deleting permission
        app()[PermissionRegistrar::class]->forgetCachedPermissions();
    }
};
