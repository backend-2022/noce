@extends('dashboard.layouts.app')

@section('pageTitle', 'إدارة النسخ الاحتياطية')

@section('content')

    <div class="inner-body">
        <div class="card">
            <div class="line-body"></div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="backups-table" class="table table-bordered tabel_style w-100"
                    data-url="{{ route('dashboard.backups.index') }}">
                    <thead>
                        <tr>
                            <th>اسم النسخة الاحتياطية</th>
                            <th>تاريخ الإنشاء</th>
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
        var currentRouteName = 'dashboard.backups.index';
        $(document).ready(function() {
            var $tableElement = $('#backups-table');
            var dataUrl = $tableElement.data('url');

            var table = initDataTable(
                '#backups-table',
                dataUrl,
                [
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'created_at',
                        name: 'created_at'
                    },
                    {
                        data: 'actions',
                        name: 'actions',
                        orderable: false,
                        searchable: false
                    }
                ]
            );

            $(document).on('click', '.delete_row', function(e) {
                e.preventDefault();
                var deleteUrl = $(this).attr('data-url');
                var backupName = $(this).attr('data-backup-name');
                var row = $(this).closest('tr');

                if (!deleteUrl) {
                    console.error('Delete URL not found');
                    return;
                }

                handleDelete(
                    deleteUrl,
                    'تم حذف النسخة الاحتياطية بنجاح',
                    'حدث خطأ أثناء حذف النسخة الاحتياطية',
                    row,
                    function() {
                        table.ajax.reload(null, false);
                    },
                    'هل أنت متأكد؟',
                    'هل تريد حذف النسخة الاحتياطية: ' + backupName + '؟\nلا يمكن التراجع عن هذا الإجراء!'
                );
            });
        });
    </script>
@endpush
