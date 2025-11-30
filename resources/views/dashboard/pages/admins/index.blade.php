@extends('dashboard.layouts.app')

@section('pageTitle', 'إدارة المشرفين')

@section('content')

    <div class="inner-body">
        <div class="card">
            <div class="line-body"></div>
            <div class="card-body">
                <div class="mb-3">
                    <a href="{{ route('dashboard.admins.create') }}" class="btn btn-primary" style="color: white;">
                        <i class="fa fa-plus"></i> إضافة مشرف
                    </a>
                </div>

                <div class="table-responsive">

                    <table id="admins-table" class="table table-bordered tabel_style w-100"
                        data-url="{{ route('dashboard.admins.index') }}"
                        data-bulk-delete-url="{{ route('dashboard.admins.bulk-destroy') }}">
                        <thead>
                            <tr>
                                <th>
                                    <div class="checkbox checkbox-primary">
                                        <input id="select-all" type="checkbox">
                                        <label for="select-all">تحديد الكل</label>
                                    </div>
                                </th>
                                <th>صورة المشرف</th>
                                <th>اسم المشرف</th>
                                <th>البريد الإلكتروني</th>
                                <th>تاريخ التسجيل</th>
                                <th>الحالة</th>
                                <th>إجراءات</th>

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
        var currentRouteName = 'dashboard.admins.index';
        $(document).ready(function() {
            var $tableElement = $('#admins-table');
            var dataUrl = $tableElement.data('url');
            var bulkDeleteUrl = $tableElement.data('bulk-delete-url');

            var table = initDataTable(
                '#admins-table',
                dataUrl,
                [{
                        data: 'select',
                        name: 'select',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'image',
                        name: 'image',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'created_at',
                        name: 'created_at'
                    },
                    {
                        data: 'status',
                        name: 'status',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'actions',
                        name: 'actions',
                        orderable: false,
                        searchable: false
                    }
                ]
            );

            function setBulkDeleteButtonState(enabled) {
                var $button = $('#datatable-delete-selected');
                if (!$button.length) {
                    return;
                }
                $button.prop('disabled', !enabled);
                $button.toggleClass('disabled', !enabled);
            }

            setBulkDeleteButtonState(false);

            function getSelectedAdminIds() {
                return $('#admins-table input.row-select:checked')
                    .map(function() {
                        return $(this).val();
                    })
                    .get();
            }

            function updateBulkDeleteState() {
                var total = $('#admins-table input.row-select').length;
                var checked = $('#admins-table input.row-select:checked').length;
                $('#select-all').prop('checked', total > 0 && total === checked);

                var hasSelection = checked > 0;
                setBulkDeleteButtonState(hasSelection);
            }

            $(document).on('change', '#select-all', function() {
                var isChecked = $(this).is(':checked');
                $('#admins-table input.row-select').prop('checked', isChecked);
                updateBulkDeleteState();
            });

            $(document).on('change', '#admins-table input.row-select', function() {
                updateBulkDeleteState();
            });

            table.on('draw.dt', function() {
                $('#select-all').prop('checked', false);
                updateBulkDeleteState();
            });

            $(document).on('datatable:delete-selected', function(event, targetSelector) {
                if (targetSelector !== '#admins-table') {
                    return;
                }
                triggerBulkDelete();
            });

            function triggerBulkDelete() {
                if (!bulkDeleteUrl) {
                    console.error('Bulk delete URL is not configured for admins table.');
                    return;
                }

                var selectedIds = getSelectedAdminIds();
                if (!selectedIds.length) {
                    showErrorAlert('يرجى اختيار مشرف واحد على الأقل للحذف.');
                    return;
                }

                handleBulkDelete(
                    bulkDeleteUrl,
                    selectedIds,
                    {
                        successMessage: 'تم حذف المشرفين المحددين بنجاح',
                        errorMessage: 'حدث خطأ أثناء حذف المشرفين',
                        confirmTitle: 'حذف المشرفين المحددين',
                        confirmText: 'هل تريد حذف المشرفين المحددين؟ لا يمكن التراجع عن هذا الإجراء!',
                        onSuccess: function() {
                            $('#select-all').prop('checked', false);
                            updateBulkDeleteState();
                            table.ajax.reload(null, false);
                        }
                    }
                );
            }

            $(document).on('click', '.delete_row', function(e) {
                e.preventDefault();
                var deleteUrl = $(this).attr('data-url');
                var adminName = $(this).attr('data-admin-name');
                var row = $(this).closest('tr');

                if (!deleteUrl) {
                    console.error('Delete URL not found');
                    return;
                }

                handleDelete(
                    deleteUrl,
                    'تم حذف المشرف بنجاح',
                    'حدث خطأ أثناء حذف المشرف',
                    row,
                    function() {
                        table.ajax.reload(null, false);
                    },
                    'هل أنت متأكد؟',
                    'هل تريد حذف المشرف: ' + adminName + '؟\nلا يمكن التراجع عن هذا الإجراء!'
                );
            });

            updateBulkDeleteState();
        });
    </script>
@endpush
