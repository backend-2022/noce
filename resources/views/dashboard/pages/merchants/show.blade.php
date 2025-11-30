@extends('dashboard.layouts.app')

@section('pageSubTitle', 'إدارة التجار')
@section('pageTitle', 'تفاصيل التاجر')
@section('searchPlaceholder', 'ابحث عن التاجر')
@section('content')

    <div class="parent_all_cards">
        <h1>نشاط التاجر</h1>
        <div class="all-cards">
            <div class="">
                <div class=" bg_card">
                    <div class="card-body">
                        <div class="media static-widget">
                            <div class="bg_img_div">
                                <img class="bg_img" src="{{ asset('assets/dashboard/images/small_icons/2.png') }}">
                            </div>
                            <div class="media-body">
                                <h4 class="mb-0 counter">{{ number_format($stats['total_orders']) }}</h4>
                                <h6 class="font-roboto">إجمالى عدد الطلبات</h6>
                                <span>إجمالي الطلبات المسجلة</span>
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
                                <h4 class="mb-0 counter">{{ number_format($stats['active_orders']) }}</h4>
                                <h6 class="font-roboto">الطلبات النشطة</h6>
                                <span>طلبات قيد التنفيذ</span>
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
                                <img class="bg_img" src="{{ asset('assets/dashboard/images/small_icons/3.png') }}">
                            </div>
                            <div class="media-body">
                                <h4 class="mb-0 counter">{{ number_format($stats['inactive_orders']) }}</h4>
                                <h6 class="font-roboto">الطلبات غير النشطة</h6>
                                <span>طلبات منتهية أو متوقفة</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div class="settings_information_section">
        <div class="flex_stuation">
            <h1>معلومات عامة</h1>

            <div class="stuation_div">
                <h2>الحالة</h2>
                <span>{{ $stats['active_orders'] > 0 ? 'مفعل' : 'غير مفعل' }}</span>
                <div class="form-check form-switch">
                    <input class="form-check-input check_styles" type="checkbox" role="switch" id="flexSwitchCheckChecked"
                        @checked($stats['active_orders'] > 0) disabled>
                </div>
            </div>
        </div>
        <div class="inputs_section">
            <div class="grid_inputs">
                <div class="div_input_label">
                    <label class="label_style">معرّف التاجر</label>
                    <input type="text" value="#{{ $merchant->merchant_id }}" readonly>
                </div>

                <div class="div_input_label">
                    <label class="label_style">اسم المتجر</label>
                    <input type="text" value="{{ $merchant->store_name }}" readonly>
                </div>

                <div class="div_input_label">
                    <label class="label_style">تاريخ التسجيل</label>
                    <input type="text" value="{{ optional($merchant->created_at)->format('Y-m-d') }}" readonly>
                </div>

                <div class="div_input_label">
                    <label class="label_style">رقم الجوال</label>
                    <input type="text" value="{{ $merchant->merchant_full_mobile }}" readonly>
                </div>
            </div>

            <div class="uplode_section">
                <h1>شعار المتجر</h1>
                <div class="upload_div" id="uploadDiv">
                    <img id="previewImg"
                        src="{{ $merchant->store_icon ?: asset('assets/dashboard/images/white_img.png') }}">
                    <span id="uploadText">{{ $merchant->store_icon ? 'الشعار الحالي' : 'لا يوجد شعار مرفوع' }}</span>
                </div>

                <input type="file" id="imageInput" accept="image/*" style="display:none;">

            </div>
        </div>

        <button class="save_informations" type="button" disabled>
            <img src="{{ asset('assets/dashboard/images/correct_wihte.png') }}"> عرض بيانات فقط
        </button>
    </div>
@endsection

@push('js')
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
        var toggleButton = document.getElementById('toggle-button');
        var hiddenContent = document.getElementById('hidden-content');

        toggleButton.addEventListener('click', function() {
            if (hiddenContent.classList.contains('hidden')) {
                // عرض المحتوى المخفي
                hiddenContent.classList.remove('hidden');
                toggleButton.textContent = 'إخفاء البحث';
            } else {
                // إخفاء المحتوى المخفي
                hiddenContent.classList.add('hidden');
                toggleButton.textContent = 'عرض البحث';
            }
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


    <!--start_upload_img -->
    <script>
        let uploadDiv = document.getElementById("uploadDiv");
        let imageInput = document.getElementById("imageInput");
        let previewImg = document.getElementById("previewImg");
        let uploadText = document.getElementById("uploadText");
        uploadDiv.addEventListener("click", () => {
            imageInput.click();
        });

        imageInput.addEventListener("change", function() {
            let file = this.files[0];

            if (file) {
                let reader = new FileReader();
                reader.onload = function(e) {
                    previewImg.src = e.target.result;

                    uploadText.style.display = "none";
                    uploadDiv.style.background = "none";
                    uploadDiv.style.border = "none";
                    uploadDiv.style.padding = "0"; // ← هنا

                    previewImg.style.width = "150px";
                    previewImg.style.height = "150px";
                    previewImg.style.objectFit = "cover";
                }
                reader.readAsDataURL(file);
            }
        });
    </script>
@endpush
