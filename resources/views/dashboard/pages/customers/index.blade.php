@extends('dashboard.layouts.app')

@section('pageTitle', 'إدارة العملاء')
@section('searchPlaceholder', 'ابحث عن العميل')

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
                                    <h6 class="font-roboto">إجمالى عدد التجار</h6>
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
                                    <h6 class="font-roboto"> عدد التجار النشطه</h6>
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
                                    <h6 class="font-roboto"> التجار المحظورين</h6>
                                    <span>+%3.1 تاجر جديد</span>
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
                        <h1 class="h1_style">التجار الأعلى نشاطاً</h1>
                    </div>

                    <div class="select_div">
                        <div class="">
                            <select class="form-select" id="exampleSelect">
                                <option selected>آخر 7 أيام</option>
                                <option value="1">آخر 6 أيام</option>
                                <option value="2">آخر 5 أيام</option>
                                <option value="3">آخر 4 أيام</option>
                            </select>
                        </div>
                    </div>
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

                    <table class="table table-bordered datatable tabel_style ">
                        <thead>
                            <tr>
                                <th>
                                    <div class="checkbox checkbox-primary">
                                        <input id="inline-1" type="checkbox">
                                        <label for="inline-1">تحديد الكل</label>
                                    </div>
                                </th>
                                <th>اسم التاجر</th>
                                <th> اسم المتجر</th>
                                <th>رقم الجوال</th>
                                <th>تاريخ التسجيل</th>
                                <th>العملاء المشتركين فى الخدمة</th>
                                <th>الحالة</th>
                                <th>إجراءات</th>

                            </tr>

                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value=""
                                            id="flexCheckDefault">
                                        <label for="flexCheckDefault">
                                        </label>
                                    </div>
                                </td>
                                <td><span class="span_styles">أحمد الشامى </span></td>
                                <td>
                                    <div class="product_details">
                                        <img src="{{ asset('assets/dashboard/images/perfum.png') }}">
                                        <span class="span_styles">عطر عود</span>
                                    </div>
                                </td>
                                <td><span class="span_styles">99625143625+</span></td>
                                <td><span class="span_styles">22 أكتوبر 2025 </span></td>
                                <td><span class="span_styles">250</span></td>
                                <td>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input check_styles" type="checkbox" role="switch"
                                            id="flexSwitchCheckChecked" checked>
                                    </div>
                                </td>
                                <td>
                                    <div class="btns-table">
                                        <a href="#" class="show_ref btn_styles">
                                            <i class="fa fa-eye"></i>
                                            عرض
                                        </a>
                                        <a href="#" class="btn_styles amendment">
                                            <i class="fa fa-edit"></i>
                                            تعديل
                                        </a>
                                        <a href="#" class="btn_styles delete_row">
                                            <i class="fa fa-trash"></i>
                                            حذف
                                        </a>
                                    </div>
                                </td>

                            </tr>
                            <tr>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value=""
                                            id="flexCheckDefault">
                                        <label for="flexCheckDefault">
                                        </label>
                                    </div>
                                </td>
                                <td><span class="span_styles">أحمد الشامى </span></td>
                                <td>
                                    <div class="product_details">
                                        <img src="{{ asset('assets/dashboard/images/perfum.png') }}">
                                        <span class="span_styles">عطر عود</span>
                                    </div>
                                </td>
                                <td><span class="span_styles">99625143625+</span></td>
                                <td><span class="span_styles">22 أكتوبر 2025 </span></td>
                                <td><span class="span_styles">250</span></td>
                                <td>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input check_styles" type="checkbox" role="switch"
                                            id="flexSwitchCheckChecked" checked>
                                    </div>
                                </td>
                                <td>
                                    <div class="btns-table">
                                        <a href="#" class="show_ref btn_styles">
                                            <i class="fa fa-eye"></i>
                                            عرض
                                        </a>
                                        <a href="#" class="btn_styles amendment">
                                            <i class="fa fa-edit"></i>
                                            تعديل
                                        </a>
                                        <a href="#" class="btn_styles delete_row">
                                            <i class="fa fa-trash"></i>
                                            حذف
                                        </a>
                                    </div>
                                </td>

                            </tr>
                            <tr>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input " type="checkbox" value=""
                                            id="flexCheckDefault">
                                        <label for="flexCheckDefault">
                                        </label>
                                    </div>
                                </td>
                                <td><span class="span_styles">أحمد الشامى </span></td>
                                <td>
                                    <div class="product_details">
                                        <img src="{{ asset('assets/dashboard/images/perfum.png') }}">
                                        <span class="span_styles">عطر عود</span>
                                    </div>
                                </td>
                                <td><span class="span_styles">99625143625+</span></td>
                                <td><span class="span_styles">22 أكتوبر 2025 </span></td>
                                <td><span class="span_styles">250</span></td>
                                <td>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input check_styles" type="checkbox" role="switch"
                                            id="flexSwitchCheckChecked" checked>
                                    </div>
                                </td>
                                <td>
                                    <div class="btns-table">
                                        <a href="#" class="show_ref btn_styles">
                                            <i class="fa fa-eye"></i>
                                            عرض
                                        </a>
                                        <a href="#" class="btn_styles amendment">
                                            <i class="fa fa-edit"></i>
                                            تعديل
                                        </a>
                                        <a href="#" class="btn_styles delete_row">
                                            <i class="fa fa-trash"></i>
                                            حذف
                                        </a>
                                    </div>
                                </td>

                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        var chartDom = document.getElementById('chart1');
        var myChart = echarts.init(chartDom);
        var option;

        option = {
            backgroundColor: '#ffffff',

            textStyle: {
                fontFamily: 'IBM Plex Sans Arabic, sans-serif'
            },

            tooltip: {
                trigger: 'item'
            },
            legend: {
                bottom: '-1%',
                icon: 'circle',
                left: 'center',
                textStyle: {
                    fontFamily: 'IBM Plex Sans Arabic, sans-serif',
                    fontSize: 15

                }
            },
            series: [{
                name: 'Access From',
                type: 'pie',
                radius: ['40%', '70%'],
                avoidLabelOverlap: false,

                padAngle: 15,
                minAngle: 15,

                itemStyle: {
                    borderRadius: 4,
                    borderColor: '#ffffff',
                    borderWidth: 10
                },

                label: {
                    show: true,
                    position: 'outside',
                    formatter: '{b}',
                    fontFamily: 'IBM Plex Sans Arabic, sans-serif',
                    fontSize: 16
                },

                labelLine: {
                    show: true,
                    length: 20,
                    length2: 20,
                    lineStyle: {
                        width: 1
                    }
                },

                emphasis: {
                    label: {
                        show: true,
                        fontSize: 20,
                        fontWeight: 'bold',
                        fontFamily: 'IBM Plex Sans Arabic, sans-serif'

                    }
                },

                data: [{
                        value: 500,
                        name: 'متجر تريت',
                        itemStyle: {
                            color: '#D6E8EE'
                        }
                    },
                    {
                        value: 500,
                        name: 'متجر الدخون',
                        itemStyle: {
                            color: '#93C2D2'
                        }
                    },
                    {
                        value: 500,
                        name: 'متجر إبداع',
                        itemStyle: {
                            color: '#02457A'
                        }
                    }
                ]
            }]
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
