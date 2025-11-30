@extends('dashboard.layouts.app')

@section('pageTitle', 'إدارة الطلبات')
@section('searchPlaceholder', 'ابحث برقم الطلب أو العميل')

@section('content')


    <section class="section_flex">
        <div class="right_part">
            <div class="all-cards all_cards_trade">
                <div class="">
                    <div class=" bg_card active">
                        <div class="card-body">
                            <div class="media static-widget">
                                <div class="bg_img_div">
                                    <img class="bg_img" src="{{ asset('assets/dashboard/images/small_icons/2.png') }}">
                                </div>
                                <div class="media-body">
                                    <h4 class="mb-0 counter">225</h4>
                                    <h6 class="font-roboto">الطلبات الكلية</h6>
                                    <span>+%3.1 آخر إسبوع</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="">
                    <div class=" bg_card">
                        <div class="card-body">
                            <div class="media static-widget">
                                <div class="bg_img_div">
                                    <img class="bg_img" src="{{ asset('assets/dashboard/images/small_icons/2.png') }}">
                                </div>
                                <div class="media-body">
                                    <h4 class="mb-0 counter">250</h4>
                                    <h6 class="font-roboto"> الطلبات المكتملة</h6>
                                    <span>+%1.8 عميل جديد</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="">
                    <div class=" bg_card">
                        <div class="card-body">
                            <div class="media static-widget">
                                <div class="bg_img_div">
                                    <img class="bg_img" src="{{ asset('assets/dashboard/images/small_icons/2.png') }}">

                                </div>
                                <div class="media-body">
                                    <h4 class="mb-0 counter">300</h4>
                                    <h6 class="font-roboto"> الطلبات قيد المراجعة</h6>
                                    <span>+%3.1 تاجر جديد</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="">
                    <div class=" bg_card">
                        <div class="card-body">
                            <div class="media static-widget">
                                <div class="bg_img_div">
                                    <img class="bg_img" src="{{ asset('assets/dashboard/images/small_icons/2.png') }}">

                                </div>
                                <div class="media-body">
                                    <h4 class="mb-0 counter">20</h4>
                                    <h6 class="font-roboto">الطلبات الملغاه</h6>
                                    <span>+%1.8 عميل جديد</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="left_part">
            <div class="card">
                <div class="card-header eachart_header">
                    <div class="trade_div">
                        <div class="div_img">
                            <img class="bg_img" src="{{ asset('assets/dashboard/images/small_icons/4.png') }}">
                        </div>
                        <div class="h1_span">
                            <h1 class="h1_style">طلبات العملاء</h1>
                            <span>1 يناير - 24 أكتوبر 2025</span>
                        </div>

                    </div>
                    <h1 class="h1_style">2570 طلب</h1>
                </div>
                <div class="card-body">
                    <div class="">
                        <div id="chart1" class="chart"></div>
                    </div>
                </div>
            </div>

        </div>

    </section>


    <div class="inner-body">
        <div class="card">
            <div class="line-body"></div>
            <div class="card-body">
                <div class="table-responsive">

                    <table id="merchant-orders-table" class="table table-bordered tabel_style w-100"
                        data-url="{{ route('dashboard.merchant-orders.index') }}"
                        data-bulk-delete-url="{{ route('dashboard.merchant-orders.bulk-destroy') }}">
                        <thead>
                            <tr>
                                <th>
                                    <div class="checkbox checkbox-primary">
                                        <input id="select-all-orders" type="checkbox">
                                        <label for="select-all-orders">تحديد الكل</label>
                                    </div>
                                </th>
                                <th>رقم الطلب</th>
                                <th>اسم التاجر</th>
                                <th>مدة التكرار بالايام</th>
                                <th>تاريخ الطلب</th>
                                <th>تاريخ انتهاء الطلب</th>
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
    <script src="{{ asset('assets/dashboard/js/datatable-handler.js') }}"></script>
    <script>
        var currentRouteName = 'dashboard.merchant-orders.index';
        $(document).ready(function() {
            var tableSelector = '#merchant-orders-table';
            var $tableElement = $(tableSelector);
            var dataUrl = $tableElement.data('url');
            var bulkDeleteUrl = $tableElement.data('bulk-delete-url');

            var table = initDataTable(
                tableSelector,
                dataUrl,
                [{
                        data: 'select',
                        name: 'select',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'order_reference',
                        name: 'order_id'
                    },
                    {
                        data: 'merchant_name',
                        name: 'merchant.store_name'
                    },
                    {
                        data: 'repeat_days',
                        name: 'repeat_at_per_day',
                        searchable: false
                    },
                    {
                        data: 'start_date',
                        name: 'start_date'
                    },
                    {
                        data: 'end_date',
                        name: 'end_date'
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

            function getSelectedOrderIds() {
                return $(tableSelector + ' input.row-select:checked')
                    .map(function() {
                        return $(this).val();
                    })
                    .get();
            }

            function updateBulkDeleteState() {
                var total = $(tableSelector + ' input.row-select').length;
                var checked = $(tableSelector + ' input.row-select:checked').length;
                $('#select-all-orders').prop('checked', total > 0 && total === checked);
                setBulkDeleteButtonState(checked > 0);
            }

            $(document).on('change', '#select-all-orders', function() {
                var isChecked = $(this).is(':checked');
                $(tableSelector + ' input.row-select').prop('checked', isChecked);
                updateBulkDeleteState();
            });

            $(document).on('change', tableSelector + ' input.row-select', function() {
                updateBulkDeleteState();
            });

            table.on('draw.dt', function() {
                $('#select-all-orders').prop('checked', false);
                updateBulkDeleteState();
            });

            $(document).on('datatable:delete-selected', function(event, targetSelector) {
                if (targetSelector !== tableSelector) {
                    return;
                }
                triggerBulkDelete();
            });

            function triggerBulkDelete() {
                if (!bulkDeleteUrl) {
                    console.error('Bulk delete URL is not configured for merchant orders table.');
                    return;
                }

                var selectedIds = getSelectedOrderIds();
                if (!selectedIds.length) {
                    showErrorAlert('يرجى اختيار طلب واحد على الأقل للحذف.');
                    return;
                }

                handleBulkDelete(
                    bulkDeleteUrl,
                    selectedIds,
                    {
                        successMessage: 'تم حذف الطلبات المحددة بنجاح',
                        errorMessage: 'حدث خطأ أثناء حذف الطلبات المحددة',
                        confirmTitle: 'حذف الطلبات المحددة',
                        confirmText: 'هل تريد حذف الطلبات المحددة؟ لا يمكن التراجع عن هذا الإجراء!',
                        onSuccess: function() {
                            $('#select-all-orders').prop('checked', false);
                            updateBulkDeleteState();
                            table.ajax.reload(null, false);
                        }
                    }
                );
            }

            $(document).on('click', '.delete-order-row', function(e) {
                e.preventDefault();
                var deleteUrl = $(this).data('url');
                var orderRef = $(this).data('order-ref') || '#';

                if (!deleteUrl) {
                    console.error('Delete URL not found for this order.');
                    return;
                }

                handleDelete(
                    deleteUrl,
                    'تم حذف الطلب بنجاح',
                    'حدث خطأ أثناء حذف الطلب',
                    null,
                    function() {
                        table.ajax.reload(null, false);
                    },
                    'حذف الطلب',
                    'هل تريد حذف الطلب ' + orderRef + '؟ لا يمكن التراجع عن هذا الإجراء!'
                );
            });

            $(document).on('change', '.toggle-order-status', function() {
                var $checkbox = $(this);
                var toggleUrl = $checkbox.data('url');

                if (!toggleUrl) {
                    console.error('Toggle URL not found for this order.');
                    $checkbox.prop('checked', !$checkbox.prop('checked'));
                    return;
                }

                handleToggle(
                    toggleUrl,
                    { _method: 'PATCH' },
                    'تم تحديث حالة الطلب بنجاح',
                    'حدث خطأ أثناء تحديث حالة الطلب',
                    function(response, success) {
                        if (!success) {
                            $checkbox.prop('checked', !$checkbox.prop('checked'));
                            return;
                        }

                        if (response && typeof response.status !== 'undefined') {
                            $checkbox.prop('checked', !!response.status);
                        }
                    }
                );
            });

            updateBulkDeleteState();
        });
    </script>

    <script>
        var chartDom = document.getElementById('chart1');
        var myChart = echarts.init(chartDom);
        var option;

        option = {
            tooltip: {
                trigger: 'axis',
                textStyle: {
                    fontFamily: 'IBM Plex Sans Arabic, sans-serif',
                    fontSize: 15
                }
            },

            xAxis: {
                type: 'category',
                data: [
                    "",
                    "يناير", "فبراير", "مارس", "أبريل", "مايو", "يونيو",
                    "يوليو", "أغسطس", "سبتمبر", "أكتوبر", "نوفمبر", "ديسمبر"
                ],
                axisLabel: {
                    fontFamily: 'IBM Plex Sans Arabic, sans-serif',
                    fontSize: 17
                }
            },

            yAxis: {
                type: 'value',
                axisLabel: {
                    fontFamily: 'IBM Plex Sans Arabic, sans-serif',
                    fontSize: 15
                }
            },

            series: [{
                    name: 'Sales',
                    type: 'bar',
                    barWidth: 20,
                    data: [
                        0,
                        200, 150, 180, 220, 170, 190,
                        210, 160, 230, 200, 250, 240
                    ],
                    itemStyle: {
                        color: '#134679',
                        borderRadius: 0
                    },
                    label: {
                        show: false,
                        fontFamily: 'IBM Plex Sans Arabic, sans-serif',
                        fontSize: 15
                    }
                },

                {
                    name: 'Trend',
                    type: 'line',
                    smooth: true,
                    symbol: 'circle',
                    symbolSize: 10,
                    lineStyle: {
                        width: 2
                    },
                    data: [
                        0,
                        200, 150, 180, 220, 170, 190,
                        210, 160, 230, 200, 250, 240
                    ],
                    label: {
                        show: false,
                        fontFamily: 'IBM Plex Sans Arabic, sans-serif',
                        fontSize: 15
                    }
                }
            ]
        };

        myChart.setOption(option);
    </script>

    <script>
        $(document).ready(function() {
            $(".set > a").on("click", function() {
                if ($(this).hasClass("active")) {
                    $(this).removeClass("active");
                    $(this)
                        .siblings(".box-custom")
                        .slideUp(200);
                    $(".set > a i")
                        .removeClass("fa-minus")
                        .addClass("fa-plus");
                } else {
                    $(".set > a i")
                        .removeClass("fa-minus")
                        .addClass("fa-plus");
                    $(this)
                        .find("i")
                        .removeClass("fa-plus")
                        .addClass("fa-minus");
                    $(".set > a").removeClass("active");
                    $(this).addClass("active");
                    $(".content").slideUp(200);
                    $(this)
                        .siblings(".box-custom")
                        .slideDown(200);
                }
            });
        });
    </script>

    <script>
        function submitForm(btn) {
            // disable the button
            btn.disabled = true;
            // submit the form
            btn.form.submit();
        }
    </script>

    <script>
        $("textarea.editor").each(function() {
            var txt = $(this).attr('name');
            CKEDITOR.replace(txt, {
                language: 'ar'
            });
        });
    </script>


    <script>
        var sidebarLinks = document.querySelectorAll('.nav-link');

        sidebarLinks.forEach(function(link) {
            link.addEventListener('click', function() {
                sidebarLinks.forEach(function(link) {
                    link.classList.remove('active');
                });
                this.classList.add('active');
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            $('.nav-link').click(function() {
                $(this).next('.sidebar-submenu').slideToggle();
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            $('.delete-raw').click(function() {
                Swal.fire({
                    title: 'هل تريد الاستمرار؟',
                    icon: 'question',
                    iconHtml: '؟',
                    confirmButtonText: 'نعم',
                    cancelButtonText: 'لا',
                    showCancelButton: true,
                    showCloseButton: true
                })
            });
        });
    </script>
@endpush
