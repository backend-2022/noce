@extends('dashboard.layouts.app')

@section('pageTitle', 'إدارة صلاحيات المشرف: ' . $admin->name)

@section('content')
    <div class="inner-body user_information">
        <form id="updatePermissionsForm" action="{{ route('dashboard.admin-permissions.update', $admin) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row">
                @foreach ($permissionsByModule as $module => $permissions)
                    <div class="col-lg-3 col-md-6 col-sm-12 mb-4">
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
                                            'utms' => 'إدارة مصادر الزيارات',
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

        });
    </script>
@endpush
