<?php

namespace App\Http\Controllers\Dashboard\AdminPermissions;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Traits\AdminActivityLogger;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Yajra\DataTables\Facades\DataTables;

class AdminPermissionController extends Controller
{
    use AdminActivityLogger;

    /**
     * Display a listing of all admins.
     */
    public function index(Request $request)
    {
        // Check permission
        if (!auth('admin')->user()->can('permissions.view')) {
            abort(403, 'Unauthorized');
        }

        if ($request->ajax()) {
            $query = Admin::query()->orderBy('created_at', 'desc');

            return DataTables::of($query)
                ->filter(function ($query) use ($request) {
                    $searchValue = $request->input('search.value');
                    if (!empty($searchValue)) {
                        $query->where(function ($builder) use ($searchValue) {
                            $builder->where('name', 'like', '%' . $searchValue . '%')
                                ->orWhere('email', 'like', '%' . $searchValue . '%')
                                ->orWhere('id', 'like', '%' . $searchValue . '%');
                        });
                    }

                    $query->orderBy('created_at', 'desc');
                }, true)
                ->addColumn('name', fn (Admin $admin) => '<span class="span_styles">' . e($admin->name) . '</span>')
                ->addColumn('email', fn (Admin $admin) => '<span class="span_styles">' . e($admin->email) . '</span>')
                ->addColumn('created_at', fn (Admin $admin) => '<span class="span_styles">' . optional($admin->created_at)->format('Y-m-d H:i') . '</span>')
                ->addColumn('permissions_count', function (Admin $admin) {
                    $count = $admin->permissions()->count();
                    return '<span class="span_styles">' . $count . '</span>';
                })
                ->addColumn('actions', function (Admin $admin) {
                    $currentAdminId = auth('admin')->id();
                    $isSelf = $admin->id === $currentAdminId;

                    if ($isSelf) {
                        // زر معطل للأدمن الحالي
                        $buttons = '<div class="btns-table">
                                        <span class="btn_styles amendment" style="cursor: not-allowed; opacity: 0.6; pointer-events: none;">
                                            <i class="fa fa-key"></i>
                                            إدارة الصلاحيات
                                        </span>
                                    </div>';
                    } else {
                        // زر عادي للأدمن الآخرين
                        $buttons = '<div class="btns-table">
                                        <a href="' . e(route('dashboard.admin-permissions.edit', $admin)) . '" class="btn_styles amendment">
                                            <i class="fa fa-key"></i>
                                            إدارة الصلاحيات
                                        </a>
                                    </div>';
                    }

                    return $buttons;
                })
                ->rawColumns(['name', 'email', 'created_at', 'permissions_count', 'actions'])
                ->make(true);
        }

        return view('dashboard.pages.admin-permissions.index');
    }

    /**
     * Show the form for editing the specified admin's permissions.
     */
    public function edit(Admin $admin)
    {
        // Check permission
        if (!auth('admin')->user()->can('permissions.view')) {
            abort(403, 'Unauthorized');
        }

        $currentAdmin = auth('admin')->user();

        // Prevent self-modification - لا يمكن لأي أدمن تعديل صلاحياته الخاصة
        if ($admin->id === $currentAdmin->id) {
            return redirect()->route('dashboard.admin-permissions.index')
                ->with('error', 'لا يمكنك تعديل صلاحياتك الخاصة من هنا');
        }

        // Get all permissions grouped by module (only view permissions)
        $allPermissions = Permission::where('guard_name', 'admin')
            ->where('name', 'like', '%.view')
            ->orderBy('name')
            ->get();

        // Get target admin's current permissions
        $targetAdminPermissions = $admin->permissions()->pluck('name')->toArray();

        // Define the order of modules
        $moduleOrder = [
            'activity-logs',
            'cities',
            'services',
            'free-services',
            'admins',
            'backup',
            'settings',
            'permissions',
            'utms',
        ];

        // Group permissions by module
        $permissionsByModule = [];
        foreach ($allPermissions as $permission) {
            $parts = explode('.', $permission->name);
            if (count($parts) === 2) {
                $module = $parts[0];
                $action = $parts[1];

                if (!isset($permissionsByModule[$module])) {
                    $permissionsByModule[$module] = [];
                }

                $permissionsByModule[$module][] = [
                    'id' => $permission->id,
                    'name' => $permission->name,
                    'action' => $action,
                    'can_assign' => true, // Allow assigning any permission
                    'is_assigned' => in_array($permission->name, $targetAdminPermissions),
                ];
            }
        }

        // Filter to only show view permissions
        foreach ($permissionsByModule as $module => &$permissions) {
            $permissions = array_filter($permissions, function ($permission) {
                return $permission['action'] === 'view';
            });
            $permissions = array_values($permissions); // Re-index array
        }

        // Reorder modules according to the defined order
        $orderedPermissionsByModule = [];
        foreach ($moduleOrder as $module) {
            if (isset($permissionsByModule[$module])) {
                $orderedPermissionsByModule[$module] = $permissionsByModule[$module];
            }
        }
        // Add any remaining modules that are not in the order list
        foreach ($permissionsByModule as $module => $permissions) {
            if (!isset($orderedPermissionsByModule[$module])) {
                $orderedPermissionsByModule[$module] = $permissions;
            }
        }

        $permissionsByModule = $orderedPermissionsByModule;

        return view('dashboard.pages.admin-permissions.edit', compact('admin', 'permissionsByModule'));
    }

    /**
     * Update the specified admin's permissions.
     */
    public function update(Request $request, Admin $admin)
    {
        // Check permission
        if (!auth('admin')->user()->can('permissions.view')) {
            abort(403, 'Unauthorized');
        }

        $currentAdmin = auth('admin')->user();

        // Prevent self-modification - لا يمكن لأي أدمن تعديل صلاحياته الخاصة
        if ($admin->id === $currentAdmin->id) {
            return redirect()->route('dashboard.admin-permissions.index')
                ->with('error', 'لا يمكنك تعديل صلاحياتك الخاصة من هنا');
        }

        // Get requested permissions from form
        $requestedPermissions = $request->input('permissions', []);

        // Get all available view permissions only
        $allAvailablePermissions = Permission::where('guard_name', 'admin')
            ->where('name', 'like', '%.view')
            ->pluck('name')
            ->toArray();

        // Allow only view permissions that exist in the system
        $allowedPermissions = array_intersect($requestedPermissions, $allAvailablePermissions);

        // Sync permissions (this will remove all and add only the allowed ones)
        $admin->syncPermissions($allowedPermissions);

        // Log the activity
        $this->logActivity('admin_permissions_updated', [
            'target_admin_id' => $admin->id,
            'target_admin_name' => $admin->name,
            'target_admin_email' => $admin->email,
            'permissions_count' => count($allowedPermissions),
            'permissions' => $allowedPermissions,
        ]);

        return redirect()->route('dashboard.admin-permissions.index')
            ->with('success', 'تم تحديث صلاحيات المشرف بنجاح');
    }
}
