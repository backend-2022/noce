@extends('dashboard.layouts.app')

@section('pageTitle', 'إدارة المدن')

@section('content')

    <div class="inner-body">
        <div class="card">
            <div class="line-body"></div>
        </div>
        <div class="card-body">
            <div class="mb-3">
                <a href="{{ route('dashboard.cities.create') }}" class="btn btn-primary" style="color: white;">
                    <i class="fa fa-plus"></i> إضافة مدينة
                </a>
            </div>

            <div class="table-responsive">
                <table id="cities-table" class="table table-bordered tabel_style w-100"
                    data-url="{{ route('dashboard.cities.index') }}">
                    <thead>
                        <tr>
                            <th>اسم المدينة</th>
                            <th>تاريخ الإضافة</th>
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
        var currentRouteName = 'dashboard.cities.index';
        $(document).ready(function() {
            var $tableElement = $('#cities-table');
            var dataUrl = $tableElement.data('url');

            var table = initDataTable(
                '#cities-table',
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
                var cityName = $(this).attr('data-city-name');
                var row = $(this).closest('tr');

                if (!deleteUrl) {
                    console.error('Delete URL not found');
                    return;
                }

                handleDelete(
                    deleteUrl,
                    'تم حذف المدينة بنجاح',
                    'حدث خطأ أثناء حذف المدينة',
                    row,
                    function() {
                        table.ajax.reload(null, false);
                    },
                    'هل أنت متأكد؟',
                    'هل تريد حذف المدينة: ' + cityName + '؟\nلا يمكن التراجع عن هذا الإجراء!'
                );
            });
        });
    </script>
@endpush
