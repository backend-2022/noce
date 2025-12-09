@extends('dashboard.layouts.app')

@section('pageTitle', 'إدارة صلاحيات المشرف: ' . $admin->name)

@section('content')
    <div class="inner-body user_information">
        <form id="updatePermissionsForm" action="{{ route('dashboard.admin-permissions.update', $admin) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row">
                @foreach ($permissionsByModule as $module => $permissions)
                    <div class="col-lg-2 col-md-2 mb-4">
                        <div class="card">
                            <div class="card-header bg-light">
                                <div class="d-flex align-items-center gap-2">
                                    @php
                                        $moduleNames = [
                                            'activity-logs' => 'سجل النشاطات',
                                            'cities' => 'المدن',
                                            'services' => 'الخدمات',
                                            'free-services' => 'الخدمات المجانية',
                                            'admins' => 'المشرفين',
                                            'backup' => 'النسخ الاحتياطي',
                                            'settings' => 'الإعدادات',
                                            'permissions' => 'الصلاحيات',
                                        ];
                                        $moduleName = $moduleNames[$module] ?? $module;

                                        // Find view permission for this module
                                        $viewPermission = collect($permissions)->firstWhere('action', 'view');
                                    @endphp
                                    @if ($viewPermission)
                                        <div class="form-check d-flex align-items-center">
                                            <input
                                                class="form-check-input view-permission-header module-{{ $module }}"
                                                type="checkbox" name="permissions[]" value="{{ $viewPermission['name'] }}"
                                                id="view_permission_{{ $viewPermission['id'] }}"
                                                {{ $viewPermission['is_assigned'] ? 'checked' : '' }}
                                                {{ !$viewPermission['can_assign'] ? 'disabled' : '' }}
                                                data-module="{{ $module }}" style="margin-top: 0; flex-shrink: 0;">
                                        </div>
                                    @endif
                                    <h6 class="mb-0" style="color: #000">
                                        {{ $moduleName }}
                                    </h6>
                                </div>
                            </div>
                            <div class="card-body">
                                @foreach ($permissions as $permission)
                                    @php
                                        $isView = $permission['action'] === 'view';
                                        // Skip view permission as it's now in header
if ($isView) {
    continue;
}
$shouldBeChecked = $permission['is_assigned'] ?? false;
                                    @endphp
                                    <div class="form-check mb-3 d-flex align-items-center">
                                        <input class="form-check-input me-2 module-{{ $module }}" type="checkbox"
                                            name="permissions[]" value="{{ $permission['name'] }}"
                                            id="permission_{{ $permission['id'] }}"
                                            data-action="{{ $permission['action'] }}"
                                            {{ $shouldBeChecked ? 'checked' : '' }}
                                            {{ !$permission['can_assign'] ? 'disabled' : '' }}
                                            style="margin-top: 0; flex-shrink: 0;">
                                        <label class="form-check-label mb-0" for="permission_{{ $permission['id'] }}"
                                            style="cursor: pointer; user-select: none;">
                                            @php
                                                $actionNames = [
                                                    'view' => 'عرض',
                                                    'create' => 'إضافة',
                                                    'update' => 'تعديل',
                                                    'delete' => 'حذف',
                                                ];
                                                $actionName =
                                                    $actionNames[$permission['action']] ?? $permission['action'];
                                            @endphp
                                            {{ $actionName }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-4">
                <button type="submit" class="save_informations" id="submitBtn">
                    <img src="{{ asset('assets/dashboard/images/correct_wihte.png') }}">
                    <span class="button-text">حفظ الصلاحيات</span>
                </button>
            </div>
        </form>
    </div>
@endsection

@push('js')
    <script>
        $(document).ready(function() {
            // Initialize form submission handler
            if (typeof handleFormSubmission === 'function') {
                handleFormSubmission('#updatePermissionsForm', {
                    successMessage: 'تم تحديث الصلاحيات بنجاح',
                    errorMessage: 'حدث خطأ أثناء تحديث الصلاحيات',
                    redirectUrl: '{{ route('dashboard.admin-permissions.index') }}',
                    redirectImmediately: true
                });
            }

            // Handle view permission change in header
            $('.view-permission-header').on('change', function() {
                const module = $(this).data('module');
                const isChecked = $(this).is(':checked');
                const otherPermissions = $(`.module-${module}:not(.view-permission-header):not(:disabled)`);

                if (!isChecked) {
                    // If view is unchecked, uncheck all other permissions
                    otherPermissions.prop('checked', false);
                }
            });

            // Handle other permissions change - if unchecked, uncheck view
            $(document).on('change', '[class*="module-"]:not(.view-permission-header)', function() {
                const $this = $(this);
                const classList = $this.attr('class').split(' ');
                let module = '';

                // Find the module class
                classList.forEach(function(cls) {
                    if (cls.startsWith('module-')) {
                        module = cls.replace('module-', '');
                    }
                });

                if (module) {
                    const viewCheckbox = $(`.view-permission-header[data-module="${module}"]`);
                    // If any permission is checked, ensure view is checked
                    const hasCheckedPermission = $(`.module-${module}:not(.view-permission-header):checked`)
                        .length > 0;
                    if (hasCheckedPermission && !viewCheckbox.is(':checked')) {
                        viewCheckbox.prop('checked', true);
                    }
                }
            });
        });
    </script>
@endpush
