@extends('dashboard.layouts.app')

@section('pageTitle', 'إدارة بيانات UTM')

@section('content')

    <div class="inner-body">
        <div class="card">
            <div class="line-body"></div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="utms-table" class="table table-bordered tabel_style w-100"
                    data-url="{{ route('dashboard.utms.index') }}">
                    <thead>
                        <tr>
                            <th>المصدر</th>
                            <th>الوسيط</th>
                            <th>الحملة</th>
                            <th>معرف الحملة</th>
                            <th>معرف مجموعة الإعلانات</th>
                            <th>اسم مجموعة الإعلانات</th>
                            <th>اسم الإعلان</th>
                            <th>معرف الإعلان</th>
                            <th>تاريخ الإضافة</th>
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
        var currentRouteName = 'dashboard.utms.index';
        $(document).ready(function() {
            var $tableElement = $('#utms-table');
            var dataUrl = $tableElement.data('url');

            // Create filter select element (will be added to search row)
            var utmSources = @json($utmSources);

            // Function to convert number to bold Unicode
            function toBoldNumber(num) {
                var boldMap = {
                    '0': '\uD835\uDFCE', '1': '\uD835\uDFCF', '2': '\uD835\uDFD0',
                    '3': '\uD835\uDFD1', '4': '\uD835\uDFD2', '5': '\uD835\uDFD3',
                    '6': '\uD835\uDFD4', '7': '\uD835\uDFD5', '8': '\uD835\uDFD6', '9': '\uD835\uDFD7'
                };
                return num.toString().split('').map(function(digit) {
                    return boldMap[digit] || digit;
                }).join('');
            }

            var filterOptions = utmSources.map(function(item) {
                var boldCount = toBoldNumber(item.count);
                return '<option value="' + item.source + '">' + item.source + ' ( ' + boldCount + ' زيارة ) </option>';
            }).join('');

            var filterHtml = '<select class="form-select form-select-solid" id="utm_source_filter" style="max-width: 200px; margin-left: 10px; height: 32px; border-radius: 50px; border: 1px solid #C7C7CC; padding: 0 15px 0 40px; font-family: \'IBM Plex Sans Arabic\', sans-serif; font-size: 14px; background-image: url(\'data:image/svg+xml;charset=utf-8,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 16 16\'%3E%3Cpath fill=\'none\' stroke=\'%23343a40\' stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M2 5l6 6 6-6\'/%3E%3C/svg%3E\'); background-repeat: no-repeat; background-position: left 15px center; background-size: 16px 12px; cursor: pointer; outline: none; -webkit-appearance: none; -moz-appearance: none; appearance: none;">' +
                '<option value="">جميع المصادر</option>' +
                filterOptions +
                '</select>';

            var table = initDataTable(
                '#utms-table',
                {
                    url: dataUrl,
                    type: 'GET',
                    data: function(d) {
                        // Add custom filter
                        d.utm_source_filter = $('#utm_source_filter').val() || '';
                    }
                },
                [{
                        data: 'utm_source',
                        name: 'utm_source'
                    },
                    {
                        data: 'utm_medium',
                        name: 'utm_medium'
                    },
                    {
                        data: 'utm_campaign',
                        name: 'utm_campaign'
                    },
                    {
                        data: 'utm_id',
                        name: 'utm_id'
                    },
                    {
                        data: 'utm_ads_set_id',
                        name: 'utm_ads_set_id'
                    },
                    {
                        data: 'utm_ads_set_name',
                        name: 'utm_ads_set_name'
                    },
                    {
                        data: 'ad_name',
                        name: 'ad_name'
                    },
                    {
                        data: 'ad_id',
                        name: 'ad_id'
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
                var utmId = $(this).attr('data-utm-id');
                var row = $(this).closest('tr');

                if (!deleteUrl) {
                    console.error('Delete URL not found');
                    return;
                }

                handleDelete(
                    deleteUrl,
                    'تم حذف بيانات UTM بنجاح',
                    'حدث خطأ أثناء حذف بيانات UTM',
                    row,
                    function() {
                        table.ajax.reload(null, false);
                    },
                    'هل أنت متأكد؟',
                    'هل تريد حذف بيانات UTM رقم: ' + utmId + '؟\nلا يمكن التراجع عن هذا الإجراء!'
                );
            });

            // Add filter to search row after table is initialized
            setTimeout(function() {
                var $wrapper = $('#utms-table').closest('.dataTables_wrapper');
                var $searchLabel = $wrapper.find('.dataTables_filter label');

                if ($searchLabel.length && !$('#utm_source_filter').length) {
                    // Add filter before the search input
                    $searchLabel.prepend(filterHtml);

                    // Add hover effect
                    $('#utm_source_filter').on('mouseenter', function() {
                        $(this).css('border-color', '#86b7fe');
                    }).on('mouseleave', function() {
                        if (!$(this).is(':focus')) {
                            $(this).css('border-color', '#C7C7CC');
                        }
                    });

                    // Add focus effect - keep border visible always
                    $('#utm_source_filter').on('focus', function() {
                        $(this).css({
                            'border': '1px solid #86b7fe',
                            'box-shadow': '0 0 0 0.25rem rgba(13, 110, 253, 0.25)'
                        });
                    }).on('blur', function() {
                        $(this).css({
                            'border': '1px solid #C7C7CC',
                            'box-shadow': 'none'
                        });
                    });

                    // Reload table when filter changes
                    $('#utm_source_filter').on('change', function() {
                        table.ajax.reload(null, false);
                    });
                }
            }, 200);
        });
    </script>
@endpush

