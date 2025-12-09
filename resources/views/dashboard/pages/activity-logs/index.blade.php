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

        function formatKeyName(key) {
            const translations = {
                // Admin related
                'admin_id': 'معرف المشرف',
                'admin_name': 'اسم المشرف',
                'admin_email': 'بريد المشرف',

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

            let html = '<div class="d-flex flex-column gap-2" style="max-width: 500px;">';

            // Filter out id fields and handle status updates specially
            const entries = Object.entries(data).filter(([key, value]) => {
                // Skip id fields (like admin_id, city_id, service_id, etc.)
                if (key.endsWith('_id') && key !== 'id') {
                    return false;
                }
                // Skip old_status and new_status if both exist (we'll show a combined message)
                if (key === 'old_status' || key === 'new_status') {
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

                if (Array.isArray(value)) {
                    displayValue = value.length > 0 ? value.join(', ') : 'فارغ';
                    valueClass = 'text-info';
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

                html += `<div class="d-flex align-items-start gap-2 p-2 rounded" style="background-color: #f8f9fa;">
                    <span class="${valueClass} flex-grow-1 fw-semibold" style="word-break: break-word; font-size: 0.85rem;">${escapeHtml(String(displayValue))}</span>
                </div>`;
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
                                return renderActivityDetails(data, type, row);
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
        });
    </script>
@endpush
