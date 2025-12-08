@extends('dashboard.layouts.app')

@section('pageTitle', 'إدارة الخدمات المجانية')

@section('content')

    <div class="inner-body">
        <div class="card">
            <div class="line-body"></div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="free-designs-table" class="table table-bordered tabel_style w-100"
                    data-url="{{ route('dashboard.free-designs.index') }}">
                    <thead>
                        <tr>
                            <th>الاسم</th>
                            <th>البريد الإلكتروني</th>
                            <th>رقم الجوال</th>
                            <th>المدينة</th>
                            <th>الخدمة</th>
                            <th>تاريخ الإضافة</th>
                            <th>إجراءات</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
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
        var currentRouteName = 'dashboard.free-designs.index';
        $(document).ready(function() {
            var $tableElement = $('#free-designs-table');
            var dataUrl = $tableElement.data('url');

            var table = initDataTable(
                '#free-designs-table',
                dataUrl,
                [
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'phone',
                        name: 'phone'
                    },
                    {
                        data: 'city',
                        name: 'city',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'service',
                        name: 'service',
                        orderable: false,
                        searchable: false
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
                var designName = $(this).attr('data-design-name');
                var row = $(this).closest('tr');

                if (!deleteUrl) {
                    console.error('Delete URL not found');
                    return;
                }

                handleDelete(
                    deleteUrl,
                    'تم حذف طلب التصميم المجاني بنجاح',
                    'حدث خطأ أثناء حذف طلب التصميم المجاني',
                    row,
                    function() {
                        table.ajax.reload(null, false);
                    },
                    'هل أنت متأكد؟',
                    'هل تريد حذف طلب: ' + designName + '؟\nلا يمكن التراجع عن هذا الإجراء!'
                );
            });
        });
    </script>
@endpush

