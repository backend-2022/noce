@extends('dashboard.layouts.app')

@section('pageTitle', 'إدارة الخدمات')

@section('content')

    <div class="inner-body">
        <div class="card">
            <div class="line-body"></div>
        </div>
        <div class="card-body">
            <div class="mb-3">
                <a href="{{ route('dashboard.services.create') }}" class="btn btn-primary" style="color: white;">
                    <i class="fa fa-plus"></i> إضافة خدمة
                </a>
            </div>

            <div class="table-responsive">
                <table id="services-table" class="table table-bordered tabel_style w-100"
                    data-url="{{ route('dashboard.services.index') }}">
                    <thead>
                        <tr>
                            <th>اسم الخدمة</th>
                            <th>الوصف</th>
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
        var currentRouteName = 'dashboard.services.index';
        $(document).ready(function() {
            var $tableElement = $('#services-table');
            var dataUrl = $tableElement.data('url');

            var table = initDataTable(
                '#services-table',
                dataUrl,
                [
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'description',
                        name: 'description'
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

            $(document).on('click', '.delete_row', function(e) {
                e.preventDefault();
                var deleteUrl = $(this).attr('data-url');
                var serviceName = $(this).attr('data-service-name');
                var row = $(this).closest('tr');

                if (!deleteUrl) {
                    console.error('Delete URL not found');
                    return;
                }

                handleDelete(
                    deleteUrl,
                    'تم حذف الخدمة بنجاح',
                    'حدث خطأ أثناء حذف الخدمة',
                    row,
                    function() {
                        table.ajax.reload(null, false);
                    },
                    'هل أنت متأكد؟',
                    'هل تريد حذف الخدمة: ' + serviceName + '؟\nلا يمكن التراجع عن هذا الإجراء!'
                );
            });
        });
    </script>
@endpush
