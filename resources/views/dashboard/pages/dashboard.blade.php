@extends('dashboard.layouts.app')

@section('content')
    <div class="row">
        <div class="all-cards margin_cards">
            <div class="mt-3">
                <div class=" bg_card active">
                    <div class="card-body">
                        <div class="media static-widget">
                            <div class="bg_img_div">
                                <img class="bg_img" src="{{ asset('assets/dashboard/images/small_icons/1.png') }}">
                            </div>
                            <div class="media-body">
                                <h4 class="mb-0 counter">225</h4>
                                <h6 class="font-roboto"> إجمالى عدد الطلبات</h6>
                                <span>+%3.1 آخر إسبوع</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-3">
                <div class=" bg_card">
                    <div class="card-body">
                        <div class="media static-widget">
                            <div class="bg_img_div">
                                <img class="bg_img" src="{{ asset('assets/dashboard/images/small_icons/2.png') }}">
                            </div>
                            <div class="media-body">
                                <h4 class="mb-0 counter">250</h4>
                                <h6 class="font-roboto"> إجمالى عدد العملاء</h6>
                                <span>+%1.8 عميل جديد</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-3">
                <div class=" bg_card">
                    <div class="card-body">
                        <div class="media static-widget">
                            <div class="bg_img_div">
                                <img class="bg_img" src="{{ asset('assets/dashboard/images/small_icons/2.png') }}">

                            </div>
                            <div class="media-body">
                                <h4 class="mb-0 counter">300</h4>
                                <h6 class="font-roboto"> إجمالى عدد التجار</h6>
                                <span>+%3.1 تاجر جديد</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-3">
                <div class=" bg_card">
                    <div class="card-body">
                        <div class="media static-widget">
                            <div class="bg_img_div">
                                <img class="bg_img" src="{{ asset('assets/dashboard/images/small_icons/3.png') }}">
                            </div>
                            <div class="media-body">
                                <h4 class="mb-0 counter">300</h4>
                                <h6 class="font-roboto"> إجمالى عدد الإشتراكات</h6>
                                <span>+%3.1 تاجر جديد</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div class="inner-body">
        <div class="card">
            <div class="card-header home_card-header">
                <h3>الطلبات الأخيرة</h3>
                <span>تابع كل طلباتك من هنا</span>
            </div>

            <form class="form search_form" method="post" action="">
                <div class="row">

                    <div id="hidden-content" class="hidden">
                        <div class="two-inputs-button">
                            <div class="two-inputs">
                                <input type="text" placeholder="اكتب اسمك هنا">
                                <input type="number" placeholder="اكتب رقم التليفون">
                                <input type="number" placeholder="اكتب رقمك القومي">
                            </div>
                            <div>
                                <button class="searching-bttn">بحث <span><i class="fa fa-search" aria-hidden="true"></i>
                                    </span></button>
                            </div>
                        </div>
                    </div>

                </div>

            </form>
            <div class="line-body"></div>

            <div class="card-body">
                <div class="table-responsive">


                    <table class="table table-bordered datatable tabel_style">
                        <thead>
                            <tr>
                                <th>
                                    <div class="checkbox checkbox-primary">
                                        <input id="inline-1" type="checkbox">
                                        <label for="inline-1">تحديد الكل</label>
                                    </div>
                                </th>
                                <th>رقم الطلب</th>
                                <th>اسم المنتج</th>
                                <th> العميل</th>
                                <th>تاريخ الطلب</th>
                                <th>السعر</th>
                                <th>الكمية</th>
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
                                <td><span class="span_styles">10248#</span></td>
                                <td>
                                    <div class="product_details">
                                        <img src="{{ asset('assets/dashboard/images/perfum.png') }}">
                                        <span class="span_styles">عطر عود</span>
                                    </div>
                                </td>

                                <td><span class="span_styles">أحمد الشامى </span></td>
                                <td><span class="span_styles">22 أكتوبر 2025 </span></td>
                                <td><span class="span_styles">250 ريال </span></td>
                                <td>
                                    <span class="span_styles">2</span>
                                </td>
                                <td>
                                    مكتمل
                                </td>
                                <td>
                                    <div class="btns-table">
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
