@extends('dashboard.layouts.app')

@section('pageTitle', 'إدارة صلاحيات المشرفين')

@section('content')

    <div class="inner-body">
        <div class="card">
            <div class="line-body"></div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="admin-permissions-table" class="table table-bordered tabel_style w-100"
                        data-url="{{ route('dashboard.admin-permissions.index') }}">
                        <thead>
                            <tr>
                                <th>اسم المشرف</th>
                                <th>البريد الإلكتروني</th>
                                <th>تاريخ التسجيل</th>
                                <th>عدد الصلاحيات</th>
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
        var currentRouteName = 'dashboard.admin-permissions.index';
        $(document).ready(function() {
            var $tableElement = $('#admin-permissions-table');
            var dataUrl = $tableElement.data('url');

            var table = initDataTable(
                '#admin-permissions-table',
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
                        data: 'created_at',
                        name: 'created_at'
                    },
                    {
                        data: 'permissions_count',
                        name: 'permissions_count',
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
        });
    </script>
@endpush
