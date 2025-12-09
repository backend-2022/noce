@extends('dashboard.layouts.app')

@section('pageTitle', 'سجل النشاطات')
@section('content')

    <div class="inner-body">
        <div class="card">
            <div class="line-body"></div>
            <div class="card-body">
                <div class="mb-3 d-flex align-items-center gap-2 flex-wrap">
                    <select class="form-select form-select-solid w-200px" id="admin_filter" style="max-width: 200px;">
                        <option value="">جميع المشرفين</option>
                        @foreach($admins as $admin)
                            <option value="{{ $admin->id }}">{{ $admin->name }} ({{ $admin->email }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="table-responsive">
                    <table id="activity-logs-table" class="table table-bordered tabel_style w-100"
                        data-url="{{ route('dashboard.activity-logs.index') }}">
                        <thead>
                            <tr>
                                <th>م</th>
                                <th>التاريخ والوقت</th>
                                <th>البريد الإلكتروني</th>
                                <th>عنوان IP</th>
                                <th>الإجراء</th>
                                <th>التفاصيل</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Log Details Modal -->
    <div class="modal fade" id="logDetailsModal" tabindex="-1" aria-labelledby="logDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="logDetailsModalLabel">تفاصيل السجل</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="logDetailsContent">
                    <!-- Content will be populated by JavaScript -->
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    @if (session('success'))
        <script>
            toastr.success(<?php echo json_encode(session('success')); ?>);
        </script>
    @endif

    @if (session('error'))
        <script>
            toastr.error(<?php echo json_encode(session('error')); ?>);
        </script>
    @endif

    <script src="{{ asset('assets/dashboard/js/datatable-handler.js') }}"></script>
    <script>
        var currentRouteName = 'dashboard.activity-logs.index';

        function formatActionName(action) {
            if (!action) {
                return 'غير محدد';
            }

            const actionTranslations = {
                'admin_created': 'إنشاء مشرف',
                'admin_updated': 'تحديث مشرف',
                'admin_deleted': 'حذف مشرف',
                'admin_bulk_deleted': 'حذف جماعي للمشرفين',
                'admin_status_toggled': 'تغيير حالة المشرف',
                'admin_permissions_updated': 'تحديث صلاحية مشرف',
                'city_created': 'إنشاء مدينة',
                'city_updated': 'تحديث مدينة',
                'city_deleted': 'حذف مدينة',
                'city_status_toggled': 'تغيير حالة المدينة',
                'service_created': 'إنشاء خدمة',
                'service_updated': 'تحديث خدمة',
                'service_deleted': 'حذف خدمة',
                'service_status_toggled': 'تغيير حالة الخدمة',
                'free_design_deleted': 'حذف تصميم مجاني',
                'backup_deleted': 'حذف نسخة احتياطية',
                'settings_updated': 'تحديث الإعدادات',
                'social_media_settings_updated': 'تحديث إعدادات التواصل الاجتماعي',
                'seo_settings_updated': 'تحديث إعدادات SEO',
            };

            if (actionTranslations[action]) {
                return actionTranslations[action];
            }

            // Fallback: try to translate common patterns
            const parts = action.split('_');
            const arabicParts = parts.map(part => {
                const wordTranslations = {
                    'created': 'إنشاء',
                    'updated': 'تحديث',
                    'deleted': 'حذف',
                    'bulk': 'جماعي',
                    'status': 'حالة',
                    'toggled': 'تغيير',
                    'admin': 'مشرف',
                    'city': 'مدينة',
                    'service': 'خدمة',
                    'free': 'مجاني',
                    'design': 'تصميم',
                    'backup': 'نسخة احتياطية',
                    'settings': 'إعدادات',
                    'social': 'تواصل',
                    'media': 'اجتماعي',
                    'seo': 'SEO',
                };

                if (wordTranslations[part.toLowerCase()]) {
                    return wordTranslations[part.toLowerCase()];
                }

                return part;
            });

            // Reorder for better Arabic reading (verb first)
            if (arabicParts.length >= 2) {
                const verb = arabicParts[0];
                const noun = arabicParts.slice(1).join(' ');
                if (verb === 'تغيير' && noun.includes('حالة')) {
                    return 'تغيير حالة ' + noun.replace('حالة ', '');
                }
                return verb + ' ' + noun;
            }

            return arabicParts.join(' ');
        }

        function formatPermissionName(permissionKey) {
            const permissionTranslations = {
                // Activity Logs
                'activity-logs.view': 'عرض سجل النشاطات',
                'activity-logs.create': 'إنشاء سجل النشاطات',
                'activity-logs.update': 'تحديث سجل النشاطات',
                'activity-logs.delete': 'حذف سجل النشاطات',
                // Cities
                'cities.view': 'عرض المدن',
                'cities.create': 'إنشاء المدن',
                'cities.update': 'تحديث المدن',
                'cities.delete': 'حذف المدن',
                // Services
                'services.view': 'عرض الخدمات',
                'services.create': 'إنشاء الخدمات',
                'services.update': 'تحديث الخدمات',
                'services.delete': 'حذف الخدمات',
                // Free Services
                'free-services.view': 'عرض الخدمات المجانية',
                'free-services.create': 'إنشاء الخدمات المجانية',
                'free-services.update': 'تحديث الخدمات المجانية',
                'free-services.delete': 'حذف الخدمات المجانية',
                // Admins
                'admins.view': 'عرض المشرفين',
                'admins.create': 'إنشاء المشرفين',
                'admins.update': 'تحديث المشرفين',
                'admins.delete': 'حذف المشرفين',
                // Backup
                'backup.view': 'عرض النسخ الاحتياطية',
                'backup.create': 'إنشاء النسخ الاحتياطية',
                'backup.update': 'تحديث النسخ الاحتياطية',
                'backup.delete': 'حذف النسخ الاحتياطية',
                // Settings
                'settings.view': 'عرض الإعدادات',
                'settings.create': 'إنشاء الإعدادات',
                'settings.update': 'تحديث الإعدادات',
                'settings.delete': 'حذف الإعدادات',
                // Permissions
                'permissions.view': 'عرض الصلاحيات',
                'permissions.create': 'إنشاء الصلاحيات',
                'permissions.update': 'تحديث الصلاحيات',
                'permissions.delete': 'حذف الصلاحيات',
            };

            if (permissionTranslations[permissionKey]) {
                return permissionTranslations[permissionKey];
            }

            // Fallback: try to translate module and action separately
            const parts = permissionKey.split('.');
            if (parts.length === 2) {
                const module = parts[0];
                const action = parts[1];

                const moduleTranslations = {
                    'activity-logs': 'سجل النشاطات',
                    'cities': 'المدن',
                    'services': 'الخدمات',
                    'free-services': 'الخدمات المجانية',
                    'admins': 'المشرفين',
                    'backup': 'النسخ الاحتياطية',
                    'settings': 'الإعدادات',
                    'permissions': 'الصلاحيات',
                };

                const actionTranslations = {
                    'view': 'عرض',
                    'create': 'إنشاء',
                    'update': 'تحديث',
                    'delete': 'حذف',
                };

                const translatedModule = moduleTranslations[module] || module;
                const translatedAction = actionTranslations[action] || action;

                return translatedAction + ' ' + translatedModule;
            }

            return permissionKey;
        }

        function formatKeyName(key) {
            const translations = {
                // Admin related
                'admin_id': 'معرف المشرف',
                'admin_name': 'اسم المشرف',
                'admin_email': 'بريد المشرف',
                'target_admin_id': 'معرف المشرف ا ',
                'target_admin_name': 'اسم المشرف ا ',
                'target_admin_email': 'بريد المشرف ا ',

                // City related
                'city_id': 'معرف المدينة',
                'city_name': 'اسم المدينة',

                // Service related
                'service_id': 'معرف الخدمة',
                'service_name': 'اسم الخدمة',

                // Free Design related
                'free_design_id': 'معرف التصميم المجاني',
                'free_design_name': 'اسم التصميم المجاني',
                'free_design_email': 'بريد التصميم المجاني',

                // Backup related
                'backup_id': 'معرف النسخة الاحتياطية',
                'backup_name': 'اسم النسخة الاحتياطية',

                // Status related
                'old_status': 'الحالة القديمة',
                'new_status': 'الحالة الجديدة',
                'status': 'الحالة',

                // Settings related
                'site_name': 'اسم الموقع',
                'site_description': 'وصف الموقع',
                'promotional_title': 'العنوان الترويجي',
                'description': 'الوصف',
                'map_link': 'رابط الخريطة',
                'keep_backups': 'الاحتفاظ بالنسخ الاحتياطية',
                'logo': 'الشعار',
                'home_banner': 'بانر الصفحة الرئيسية',
                'meta_title': 'عنوان SEO',
                'meta_description': 'وصف SEO',
                'meta_keywords': 'كلمات مفتاحية SEO',
                'facebook': 'فيسبوك',
                'twitter': 'تويتر',
                'instagram': 'إنستغرام',
                'youtube': 'يوتيوب',
                'linkedin': 'لينكد إن',
                'whatsapp': 'واتساب',
                'phone': 'الهاتف',
                'email': 'البريد الإلكتروني',
                'address': 'العنوان',

                // Permissions related
                'permissions': 'الصلاحيات',
                'permissions_count': 'عدد الصلاحيات',

                // General
                'count': 'العدد',
                'updated_keys': 'المفاتيح المحدثة',
                'admins': 'المشرفين',
                'id': 'المعرف',
                'name': 'الاسم',
            };

            if (translations[key]) {
                return translations[key];
            }

            // Convert snake_case to readable Arabic text
            const parts = key.split('_');
            const arabicParts = parts.map(part => {
                if (/^\d+$/.test(part)) {
                    return part;
                }

                const wordTranslations = {
                    'id': 'معرف',
                    'name': 'اسم',
                    'email': 'بريد',
                    'status': 'حالة',
                    'old': 'قديم',
                    'new': 'جديد',
                    'free': 'مجاني',
                    'design': 'تصميم',
                    'admin': 'مشرف',
                    'city': 'مدينة',
                    'service': 'خدمة',
                    'backup': 'نسخة احتياطية',
                    'count': 'عدد',
                    'updated': 'محدث',
                    'keys': 'مفاتيح',
                    'site': 'موقع',
                    'description': 'وصف',
                    'promotional': 'ترويجي',
                    'title': 'عنوان',
                    'map': 'خريطة',
                    'link': 'رابط',
                    'keep': 'احتفاظ',
                    'backups': 'نسخ احتياطية',
                    'logo': 'شعار',
                    'home': 'رئيسية',
                    'banner': 'بانر',
                    'meta': 'SEO',
                    'keywords': 'كلمات مفتاحية',
                    'facebook': 'فيسبوك',
                    'twitter': 'تويتر',
                    'instagram': 'إنستغرام',
                    'youtube': 'يوتيوب',
                    'linkedin': 'لينكد إن',
                    'whatsapp': 'واتساب',
                    'phone': 'هاتف',
                    'address': 'عنوان',
                };

                if (wordTranslations[part.toLowerCase()]) {
                    return wordTranslations[part.toLowerCase()];
                }

                return part;
            });

            return arabicParts.join(' ');
        }

        function renderActivityDetails(data, type, row) {
            if (!data || (typeof data === 'object' && Object.keys(data).length === 0)) {
                return '<span class="text-muted fst-italic">لا توجد تفاصيل</span>';
            }

            if (typeof data === 'string') {
                try {
                    data = JSON.parse(data);
                } catch (e) {
                    return '<span class="text-gray-700">' + escapeHtml(data || '-') + '</span>';
                }
            }

            if (typeof data !== 'object') {
                return '<span class="text-gray-700">' + escapeHtml(String(data || '-')) + '</span>';
            }

            // Check if this is a bulk deletion action
            const action = row?.action || row?.raw_data?.action || '';
            const isBulkDelete = action.includes('bulk_deleted');

            // Handle bulk deletion specially
            if (isBulkDelete && data.count !== undefined) {
                // Find the array of deleted items (could be 'admins', 'cities', 'services', etc.)
                let deletedItems = null;
                let itemsKey = null;

                // Check common keys for bulk deletion arrays
                const possibleKeys = ['admins', 'cities', 'services', 'items', 'deleted_items'];
                for (const key of possibleKeys) {
                    if (Array.isArray(data[key]) && data[key].length > 0) {
                        deletedItems = data[key];
                        itemsKey = key;
                        break;
                    }
                }

                let html = '<div class="d-flex flex-column gap-2" style="max-width: 500px;">';

                // Show count
                html += `<div class="d-flex align-items-start gap-2 p-2 rounded" style="background-color: #f8f9fa;">
                    <span class="text-gray-700 fw-semibold" style="word-break: break-word; font-size: 0.85rem;">عدد العناصر المحذوفة: ${data.count}</span>
                </div>`;

                // Show list of deleted items
                if (deletedItems && deletedItems.length > 0) {
                    deletedItems.forEach((item, index) => {
                        const itemName = item.name || item.city_name || item.service_name || item.title || 'غير محدد';
                        const itemEmail = item.email || '';
                        const itemId = item.id || '';

                        let itemText = `${index + 1}. ${escapeHtml(itemName)}`;
                        if (itemEmail) {
                            itemText += ` (${escapeHtml(itemEmail)})`;
                        }
                        // Hide ID from display

                        html += `<div class="d-flex align-items-start gap-2 p-2 rounded" style="background-color: #f8f9fa;">
                            <span class="text-gray-700 flex-grow-1" style="word-break: break-word; font-size: 0.85rem;">${itemText}</span>
                        </div>`;
                    });
                }

                html += '</div>';
                return html;
            }

            let html = '<div class="d-flex flex-column gap-2" style="max-width: 500px;">';

            // Filter out id fields and handle status updates specially
            const entries = Object.entries(data).filter(([key, value]) => {
                // Skip all id fields (like admin_id, city_id, service_id, id, target_admin_id, etc.)
                if (key.endsWith('_id') || key === 'id') {
                    return false;
                }
                // Skip old_status and new_status if both exist (we'll show a combined message)
                if (key === 'old_status' || key === 'new_status') {
                    return false;
                }
                // Skip bulk deletion specific fields (handled above)
                if (isBulkDelete && (key === 'count' || key === 'admins' || key === 'cities' || key === 'services' || key === 'items' || key === 'deleted_items')) {
                    return false;
                }
                return value !== null && value !== undefined && value !== '';
            });

            // Check if this is a status update
            const isStatusUpdate = data.hasOwnProperty('old_status') || data.hasOwnProperty('new_status');

            if (entries.length === 0 && !isStatusUpdate) {
                return '<span class="text-muted fst-italic">لا توجد تفاصيل</span>';
            }

            // Add status update message if applicable
            if (isStatusUpdate) {
                html += `<div class="d-flex align-items-start gap-2 p-2 rounded" style="background-color: #f8f9fa;">
                    <span class="text-gray-700 flex-grow-1 fw-semibold" style="word-break: break-word; font-size: 0.85rem;">تم تحديث الحالة</span>
                </div>`;
            }

            for (const [key, value] of entries) {
                let displayValue = value;
                let valueClass = 'text-gray-700';
                let isArrayOfObjects = false;

                // Check if this is a permissions array
                const isPermissionsArray = key === 'permissions' && Array.isArray(value) && value.length > 0 && typeof value[0] === 'string';

                if (Array.isArray(value)) {
                    // Check if array contains objects (like in bulk operations)
                    if (value.length > 0 && typeof value[0] === 'object') {
                        isArrayOfObjects = true;
                        // Build HTML structure for array of objects
                        let arrayHtml = '<div class="d-flex flex-column gap-1">';
                        value.forEach((item, idx) => {
                            if (item.name || item.city_name || item.service_name) {
                                const name = item.name || item.city_name || item.service_name;
                                const email = item.email ? ` (${escapeHtml(item.email)})` : '';
                                arrayHtml += `<div class="text-info" style="font-size: 0.85rem;">${idx + 1}. ${escapeHtml(name)}${email}</div>`;
                            } else {
                                arrayHtml += `<div class="text-info" style="font-size: 0.85rem;">${idx + 1}. ${escapeHtml(JSON.stringify(item))}</div>`;
                            }
                        });
                        arrayHtml += '</div>';
                        displayValue = arrayHtml;
                        valueClass = 'text-info';
                    } else if (isPermissionsArray) {
                        // Handle permissions array - translate each permission key and display as badges
                        let permissionsHtml = '<div class="d-flex flex-wrap gap-1">';
                        value.forEach((permission) => {
                            const translatedPermission = formatPermissionName(permission);
                        });
                        permissionsHtml += '</div>';
                        displayValue = permissionsHtml;
                        valueClass = 'text-info';
                    } else {
                        displayValue = value.length > 0 ? value.join(', ') : 'فارغ';
                        valueClass = 'text-info';
                    }
                } else if (typeof value === 'object') {
                    displayValue = JSON.stringify(value, null, 2);
                    valueClass = 'text-gray-600';
                } else if (typeof value === 'boolean') {
                    displayValue = value ? 'نعم' : 'لا';
                    valueClass = value ? 'text-success' : 'text-danger';
                } else if (typeof value === 'number') {
                    displayValue = value.toString();
                    valueClass = 'text-primary';
                }

                const cleanKey = formatKeyName(key);

                if (isArrayOfObjects) {
                    // For arrays of objects, displayValue already contains HTML
                    html += `<div class="d-flex align-items-start gap-2 p-2 rounded" style="background-color: #f8f9fa;">
                        <div class="${valueClass} flex-grow-1 fw-semibold" style="word-break: break-word; font-size: 0.85rem;">${displayValue}</div>
                    </div>`;
                } else if (isPermissionsArray) {
                    // For permissions array, displayValue already contains HTML (badges)
                    html += `<div class="d-flex align-items-start gap-2 p-2 rounded" style="background-color: #f8f9fa;">
                        <div class="${valueClass} flex-grow-1" style="word-break: break-word;">${displayValue}</div>
                    </div>`;
                } else {
                    html += `<div class="d-flex align-items-start gap-2 p-2 rounded" style="background-color: #f8f9fa;">
                        <span class="${valueClass} flex-grow-1 fw-semibold" style="word-break: break-word; font-size: 0.85rem;">${escapeHtml(String(displayValue))}</span>
                    </div>`;
                }
            }

            html += '</div>';
            return html;
        }

        function escapeHtml(text) {
            if (text === null || text === undefined) {
                return '';
            }
            const div = document.createElement('div');
            div.textContent = String(text);
            return div.innerHTML;
        }

        function formatTimestamp(timestamp) {
            try {
                if (!timestamp) {
                    return 'غير محدد';
                }
                const date = new Date(timestamp);
                if (isNaN(date.getTime())) {
                    return timestamp;
                }
                // Format as DD-MM-YYYY HH:mm
                const day = String(date.getDate()).padStart(2, '0');
                const month = String(date.getMonth() + 1).padStart(2, '0');
                const year = date.getFullYear();
                const hours = String(date.getHours()).padStart(2, '0');
                const minutes = String(date.getMinutes()).padStart(2, '0');
                return `${hours}:${minutes} ${day}-${month}-${year}`;
            } catch (e) {
                console.error('Error formatting timestamp:', e);
                return timestamp;
            }
        }

        function renderLogObjectDetails(logData) {
            if (!logData || typeof logData !== 'object') {
                return 'لا توجد بيانات';
            }

            let html = '<pre style="white-space: pre-wrap; word-wrap: break-word; font-family: inherit; font-size: 0.9rem; margin: 0; padding: 0;">';

            const entries = Object.entries(logData).filter(([key, value]) =>
                value !== null && value !== undefined && value !== ''
            );

            if (entries.length === 0) {
                return 'لا توجد بيانات';
            }

            entries.forEach(([key, value]) => {
                const keyLabel = formatKeyName(key);
                let displayValue = value;

                // Handle different value types
                if (Array.isArray(value)) {
                    if (value.length === 0) {
                        displayValue = 'فارغ';
                    } else if (typeof value[0] === 'object') {
                        displayValue = JSON.stringify(value, null, 2);
                    } else {
                        displayValue = value.join(', ');
                    }
                } else if (typeof value === 'object') {
                    displayValue = JSON.stringify(value, null, 2);
                } else if (typeof value === 'boolean') {
                    displayValue = value ? 'نعم' : 'لا';
                } else {
                    displayValue = String(value);
                }

                // Format special fields
                if (key === 'timestamp' && typeof value === 'string') {
                    displayValue = formatTimestamp(value);
                } else if (key === 'action' && typeof value === 'string') {
                    displayValue = formatActionName(value);
                }

                html += escapeHtml(keyLabel) + ': ' + escapeHtml(String(displayValue)) + '\n';
            });

            html += '</pre>';
            return html;
        }

        $(document).ready(function() {
            var $tableElement = $('#activity-logs-table');
            var dataUrl = $tableElement.data('url');

            var table = initDataTable(
                '#activity-logs-table',
                {
                    url: dataUrl,
                    type: 'GET',
                    data: function(d) {
                        // Add custom filters
                        d.admin_id = $('#admin_filter').val() || '';
                    },
                    dataSrc: function(json) {
                        if (json.error) {
                            console.error('Activity Log Error:', json.error);
                            return [];
                        }
                        return json.data || [];
                    },
                    error: function(xhr, error, thrown) {
                        console.error('DataTables Ajax Error:', {
                            xhr: xhr,
                            error: error,
                            thrown: thrown,
                            responseText: xhr.responseText
                        });
                    }
                },
                [
                    {
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'timestamp',
                        name: 'timestamp'
                    },
                    {
                        data: 'admin_email',
                        name: 'admin_email'
                    },
                    {
                        data: 'ip_address',
                        name: 'ip_address'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        render: function(data, type, row) {
                            if (type === 'display') {
                                return '<span class="text-gray-700 fw-semibold">' + escapeHtml(formatActionName(data)) + '</span>';
                            }
                            return data;
                        }
                    },
                    {
                        data: 'details',
                        name: 'details',
                        orderable: false,
                        searchable: true,
                        render: function(data, type, row) {
                            if (type === 'display') {
                                let detailsHtml = renderActivityDetails(data, type, row);

                                // Add eye icon button
                                try {
                                    const rawData = row.raw_data || row || {};
                                    const jsonString = JSON.stringify(rawData);
                                    const encodedData = btoa(unescape(encodeURIComponent(jsonString)));
                                    const buttonHtml = '<div class="mt-2"><button type="button" class="btn btn-sm btn-primary view-log-btn" data-log-encoded="' + encodedData + '" title="عرض التفاصيل الكاملة" style="min-width: 40px; padding: 0.25rem 0.5rem;"><i class="fa fa-eye"></i></button></div>';
                                    return detailsHtml + buttonHtml;
                                } catch (e) {
                                    console.error('Error rendering view button:', e, row);
                                    return detailsHtml;
                                }
                            }
                            return JSON.stringify(data);
                        }
                    }
                ]
            );

            window.activityLogsTable = table;

            // Filter change handlers
            $('#admin_filter').on('change', function() {
                table.ajax.reload(null, false);
            });

            // Handle view log details button click
            $(document).on('click', '.view-log-btn', function() {
                try {
                    const encodedData = $(this).attr('data-log-encoded');
                    // Decode Unicode-safe base64
                    const jsonString = decodeURIComponent(escape(atob(encodedData)));
                    const logData = JSON.parse(jsonString);
                    const modalContent = renderLogObjectDetails(logData);

                    $('#logDetailsContent').html(modalContent);
                    $('#logDetailsModal').modal('show');
                } catch (e) {
                    console.error('Error parsing log data:', e);
                    toastr.error('حدث خطأ في تحميل تفاصيل السجل');
                }
            });
        });
    </script>
@endpush
