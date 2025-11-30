@extends('dashboard.layouts.app')

@section('pageTitle', 'الملف الشخصي')

@section('content')
    <div class="inner-body user_information">
        <form id="profileForm" action="{{ route('dashboard.profile.update') }}" method="POST" enctype="multipart/form-data" autocomplete="off">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-12 mt-3">
                    <div class="div_input_label">
                        <label class="label_style" for="name">اسم المستخدم</label>
                        <input type="text"
                               id="name"
                               name="name"
                               class="form-control"
                               value="{{ old('name', $admin->name ?? '') }}"
                               placeholder="اسم المستخدم">
                    </div>
                </div>

                <div class="col-lg-6 col-md-6 col-sm-12 mt-3">
                    <div class="div_input_label">
                        <label class="label_style" for="email">البريد الالكتروني</label>
                        <input type="text"
                               id="email"
                               name="email"
                               class="form-control"
                               value="{{ old('email', $admin->email ?? '') }}"
                               placeholder="example@mail.com">
                    </div>
                </div>

                <div class="col-lg-6 col-md-6 col-sm-12 mt-3">
                    <div class="div_input_label">
                        <label class="label_style" for="password">كلمة المرور (اتركه فارغاً إذا لم ترد تغييره)</label>
                        <input type="password"
                               id="password"
                               name="password"
                               class="form-control"
                               placeholder="كلمة المرور الجديدة"
                               autocomplete="new-password">
                    </div>
                </div>

                <div class="col-lg-6 col-md-6 col-sm-12 mt-3">
                    <div class="div_input_label">
                        <label class="label_style" for="password_confirmation">تأكيد كلمة المرور</label>
                        <input type="password"
                               id="password_confirmation"
                               name="password_confirmation"
                               class="form-control"
                               placeholder="تأكيد كلمة المرور"
                               autocomplete="new-password">
                    </div>
                </div>

                <div class="col-lg-2 col-md-6 col-sm-12 mt-3">
                    <div class="uplode_section">
                        <div class="upload_div" id="uploadDiv">
                            @php
                                $imageUrl = isset($admin) && $admin->image
                                    ? getFileFullUrl($admin->image, $admin->id, 'public', 'white_img.png')
                                    : asset('assets/dashboard/images/white_img.png');
                            @endphp
                            <img id="previewImg" src="{{ $imageUrl }}" alt="Profile Image">
                            <span id="uploadText">إضغط لاختيار الصورة</span>
                        </div>
                        <input type="file"
                               id="imageInput"
                               name="image"
                               accept="image/*"
                               style="display:none;">
                    </div>
                </div>
            </div>

            <button type="submit" class="save_informations" id="submitBtn">
                <img src="{{ asset('assets/dashboard/images/correct_wihte.png') }}">
                <span class="button-text">حفظ البيانات</span>
            </button>
        </form>
    </div>
@endsection

@push('js')
    <script>
        $(document).ready(function() {
            // Initialize form submission handler
            if (typeof handleFormSubmission === 'function') {
                handleFormSubmission('#profileForm', {
                    successMessage: 'تم تحديث الملف الشخصي بنجاح',
                    errorMessage: 'حدث خطأ أثناء تحديث الملف الشخصي',
                    redirectUrl: '{{ route('dashboard.profile.index') }}',
                    redirectImmediately: true,
                    afterSubmit: function(response) {
                        // Update image preview if image was updated
                        if (response.data && response.data.admin && response.data.admin.image) {
                            const previewImg = document.getElementById('previewImg');
                            const uploadText = document.getElementById('uploadText');
                            const uploadDiv = document.getElementById('uploadDiv');

                            if (previewImg) {
                                previewImg.src = response.data.admin.image;
                                // Apply preview styling after image update
                                if (typeof applyImagePreviewStyling === 'function') {
                                    previewImg.addEventListener('load', function applyStyle() {
                                        applyImagePreviewStyling(previewImg, uploadDiv, uploadText);
                                        previewImg.removeEventListener('load', applyStyle);
                                    });
                                    // If image is already cached/loaded
                                    if (previewImg.complete) {
                                        previewImg.dispatchEvent(new Event('load'));
                                    }
                                }
                            }
                        }
                        // Clear any previous validation errors on success
                        $('#profileForm').find('.is-invalid').removeClass('is-invalid');
                        $('#profileForm').find('.error-container').remove();
                    }
                });
            } else {
                console.error('ajax-handler.js is not loaded. Please ensure the script is included.');
            }

            // Clear validation errors when user starts typing/changing inputs
            $('#profileForm input, #profileForm textarea, #profileForm select').on('input change', function() {
                if (this.classList.contains('is-invalid')) {
                    this.classList.remove('is-invalid');
                    const errorContainer = $(this).closest('.div_input_label, .uplode_section').find('.error-container');
                    if (errorContainer.length) {
                        errorContainer.remove();
                    }
                }
            });

            // Image upload preview
            if (typeof setupImageUpload === 'function') {
                setupImageUpload('uploadDiv', 'imageInput', 'previewImg', 'uploadText');
            }
        });
    </script>
@endpush
