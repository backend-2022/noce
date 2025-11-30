@extends('dashboard.layouts.app')

@section('pageTitle', 'تعديل المشرف')

@section('content')
    <div class="inner-body user_information">
        <form id="updateAdminForm" action="{{ route('dashboard.admins.update', $admin) }}" method="POST" enctype="multipart/form-data" autocomplete="off">
            @csrf
            @method('PUT')

            <div class="row">
                <!-- Image Upload -->
                <div class="col-lg-12 col-md-12 col-sm-12 mt-3">
                    <div class="div_input_label">
                        <div class="uplode_section image-input">
                            <div class="image-input-wrapper">
                                <div class="upload_div" id="uploadDivImage">
                                    <img id="previewImg" src="{{ $imageUrl }}" alt="{{ $admin->name }}">
                                    <span id="uploadText" style="display: {{ $imageUrl ? 'none' : 'block' }};">صورة المشرف</span>
                                </div>
                                <input type="file" id="imageInput" name="image" accept="image/*" style="display:none;">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Name -->
                <div class="col-lg-6 col-md-6 col-sm-12 mt-3">
                    <div class="div_input_label">
                        <label class="label_style" for="name">الاسم <span class="text-danger">*</span></label>
                        <input type="text" id="name" name="name"
                            class="form-control" value="{{ old('name', $admin->name) }}"
                            placeholder="أدخل الاسم"
                            autocomplete="off"
                            data-lpignore="true">
                    </div>
                </div>

                <!-- Email -->
                <div class="col-lg-6 col-md-6 col-sm-12 mt-3">
                    <div class="div_input_label">
                        <label class="label_style" for="email">البريد الإلكتروني <span
                                class="text-danger">*</span></label>
                        <input type="text" id="email" name="email"
                            class="form-control" value="{{ old('email', $admin->email) }}"
                            placeholder="example@mail.com"
                            autocomplete="off"
                            data-lpignore="true">
                    </div>
                </div>

                <!-- Password -->
                <div class="col-lg-6 col-md-6 col-sm-12 mt-3">
                    <div class="div_input_label">
                        <label class="label_style" for="password">كلمة المرور الجديدة (اتركها فارغة إذا لم ترد التغيير)</label>
                        <input type="password" id="password" name="password"
                            class="form-control" placeholder="********"
                            autocomplete="new-password"
                            data-lpignore="true">
                        <small class="text-muted">يجب أن تحتوي على حرف كبير وحرف صغير ورقم ورمز خاص (8 أحرف على
                            الأقل)</small>
                    </div>
                </div>

                <!-- Password Confirmation -->
                <div class="col-lg-6 col-md-6 col-sm-12 mt-3">
                    <div class="div_input_label">
                        <label class="label_style" for="password_confirmation">تأكيد كلمة المرور الجديدة</label>
                        <input type="password" id="password_confirmation" name="password_confirmation"
                            class="form-control"
                            placeholder="********"
                            autocomplete="new-password"
                            data-lpignore="true">
                    </div>
                </div>

                <!-- Is Active -->
                <div class="col-lg-12 col-md-12 col-sm-12 mt-3">
                    <div class="div_input_label">
                        <div class="form-check form-switch">
                            <input class="form-check-input check_styles" type="checkbox" role="switch" id="is_active" name="is_active"
                                value="1" {{ old('is_active', $admin->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">
                                تفعيل المشرف
                            </label>
                        </div>
                    </div>
                </div>

            </div>

            <button type="submit" class="save_informations" id="submitBtn">
                <img src="{{ asset('assets/dashboard/images/correct_wihte.png') }}">
                <span class="button-text">تحديث المشرف</span>
            </button>
        </form>
    </div>
@endsection

@push('js')
    <script>
        $(document).ready(function() {
            // Initialize form submission handler
            if (typeof handleFormSubmission === 'function') {
                handleFormSubmission('#updateAdminForm', {
                    successMessage: 'تم تحديث المشرف بنجاح',
                    errorMessage: 'حدث خطأ أثناء تحديث المشرف',
                    redirectUrl: '{{ route('dashboard.admins.index') }}',
                    redirectImmediately: true
                });
            }

            // Image upload preview
            if (typeof setupImageUpload === 'function') {
                setupImageUpload('uploadDivImage', 'imageInput', 'previewImg', 'uploadText');
            }
        });
    </script>
@endpush
