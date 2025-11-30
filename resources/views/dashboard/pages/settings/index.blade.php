@extends('dashboard.layouts.app')

@section('content')
    <div class="inner-body user_information">
        <div class="contentt mb-4">
            <a class="ref_settings active" href="{{ route('dashboard.settings.index') }}">عام</a>
            <a class="ref_settings" href="{{ route('dashboard.settings.social-media.index') }}">روابط التواصل الاجتماعي</a>
        </div>

        <form id="settingsForm" action="{{ route('dashboard.settings.update') }}" method="POST" enctype="multipart/form-data" autocomplete="off">
            @csrf
            @method('PUT')

            <div class="row">
                <!-- Logo Light -->
                <div class="col-lg-6 col-md-6 col-sm-12 mt-3">
                    <div class="div_input_label">
                        <div class="uplode_section">
                            <div class="upload_div" id="uploadDivLight">
                                @php
                                    $logoLightUrl = isset($settings['logo_light']) && $settings['logo_light']
                                        ? getFileFullUrl($settings['logo_light'], null, 'public', 'white_img.png')
                                        : asset('assets/dashboard/images/white_img.png');
                                @endphp
                                <img id="previewImgLight" src="{{ $logoLightUrl }}" alt="Logo Light">
                                <span id="uploadTextLight">اللوجو الفاتح</span>
                            </div>
                            <input type="file"
                                   id="logoLightInput"
                                   name="logo_light"
                                   accept="{{ \App\Enums\MimesValidationEnums\IconMimesValidationEnum::asRuleString() }}"
                                   style="display:none;">
                        </div>
                    </div>
                </div>

                <!-- Logo Dark -->
                <div class="col-lg-6 col-md-6 col-sm-12 mt-3">
                    <div class="div_input_label">
                        <div class="uplode_section">
                            <div class="upload_div" id="uploadDivDark">
                                @php
                                    $logoDarkUrl = isset($settings['logo_dark']) && $settings['logo_dark']
                                        ? getFileFullUrl($settings['logo_dark'], null, 'public', 'white_img.png')
                                        : asset('assets/dashboard/images/white_img.png');
                                @endphp
                                <img id="previewImgDark" src="{{ $logoDarkUrl }}" alt="Logo Dark">
                                <span id="uploadTextDark">اللوجو الداكن</span>
                            </div>
                            <input type="file"
                                   id="logoDarkInput"
                                   name="logo_dark"
                                   accept="{{ \App\Enums\MimesValidationEnums\IconMimesValidationEnum::asRuleString() }}"
                                   style="display:none;">
                        </div>
                    </div>
                </div>

                <!-- Title -->
                <div class="col-lg-6 col-md-6 col-sm-12 mt-3">
                    <div class="div_input_label">
                        <label class="label_style" for="title">عنوان الموقع</label>
                        <input type="text"
                               id="title"
                               name="title"
                               class="form-control"
                               value="{{ old('title', $settings['title'] ?? '') }}"
                               placeholder="عنوان الموقع">
                    </div>
                </div>

                <!-- Email -->
                <div class="col-lg-6 col-md-6 col-sm-12 mt-3">
                    <div class="div_input_label">
                        <label class="label_style" for="email">البريد الإلكتروني</label>
                        <input type="text"
                               id="email"
                               name="email"
                               class="form-control"
                               value="{{ old('email', $settings['email'] ?? '') }}"
                               placeholder="example@mail.com">
                    </div>
                </div>

                <!-- Phone Code -->
                <div class="col-lg-2 col-md-6 col-sm-12 mt-3">
                    <div class="div_input_label">
                        <label class="label_style" for="phone_code">كود الدولة</label>
                        <select class="form-select" name="phone_code" id="phone_code_select">
                        </select>
                    </div>
                </div>

                <!-- Phone -->
                <div class="col-lg-4 col-md-6 col-sm-12 mt-3">
                    <div class="div_input_label">
                        <label class="label_style" for="phone">رقم الهاتف</label>
                        <input type="number"
                               id="phone"
                               name="phone"
                               class="form-control"
                               value="{{ old('phone', $settings['phone'] ?? '') }}"
                               placeholder="5xxxxxxxx"
                               >
                    </div>
                </div>

                <!-- WhatsApp -->
                <div class="col-lg-6 col-md-6 col-sm-12 mt-3">
                    <div class="div_input_label">
                        <label class="label_style" for="whatsapp">رقم الواتساب</label>
                        <input type="number"
                               id="whatsapp"
                               name="whatsapp"
                               class="form-control"
                               value="{{ old('whatsapp', $settings['whatsapp'] ?? '') }}"
                               placeholder="5xxxxxxxx">
                    </div>
                </div>

                <!-- Extension URL -->
                <div class="col-lg-6 col-md-6 col-sm-12 mt-3">
                    <div class="div_input_label">
                        <label class="label_style" for="extension_url">رابط الإضافة</label>
                        <input type="text"
                               id="extension_url"
                               name="extension_url"
                               class="form-control"
                               value="{{ old('extension_url', $settings['extension_url'] ?? '') }}"
                               placeholder="https://example.com">
                    </div>
                </div>

                <!-- About -->
                <div class="col-lg-12 col-md-12 col-sm-12 mt-3">
                    <div class="div_input_label">
                        <label class="label_style" for="about">نبذة عن الموقع</label>
                        <textarea id="about"
                                  name="about"
                                  class="form-control"
                                  rows="4"
                                  placeholder="نبذة عن الموقع">{{ old('about', $settings['about'] ?? '') }}</textarea>
                    </div>
                </div>

                <!-- Terms and Conditions -->
                <div class="col-lg-12 col-md-12 col-sm-12 mt-3">
                    <div class="div_input_label">
                        <label class="label_style" for="terms_and_conditions">الشروط والأحكام</label>
                        <textarea id="terms_and_conditions"
                                  name="terms_and_conditions"
                                  class="form-control"
                                  rows="6"
                                  placeholder="الشروط والأحكام">{{ old('terms_and_conditions', $settings['terms_and_conditions'] ?? '') }}</textarea>
                    </div>
                </div>

                <!-- Privacy Policy -->
                <div class="col-lg-12 col-md-12 col-sm-12 mt-3">
                    <div class="div_input_label">
                        <label class="label_style" for="privacy_policy">سياسة الخصوصية</label>
                        <textarea id="privacy_policy"
                                  name="privacy_policy"
                                  class="form-control"
                                  rows="6"
                                  placeholder="سياسة الخصوصية">{{ old('privacy_policy', $settings['privacy_policy'] ?? '') }}</textarea>
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
                handleFormSubmission('#settingsForm', {
                    successMessage: 'تم تحديث الإعدادات بنجاح',
                    errorMessage: 'حدث خطأ أثناء تحديث الإعدادات',
                    afterSubmit: function(response) {
                        // Clear any previous validation errors on success
                        $('#settingsForm').find('.is-invalid').removeClass('is-invalid');
                        $('#settingsForm').find('.error-container').remove();
                    }
                });
            } else {
                console.error('ajax-handler.js is not loaded. Please ensure the script is included.');
            }
            // Initialize phone code functionality
            @php
                $phoneCode = old('phone_code', $settings['phone_code'] ?? '+966');
                $phoneCode = (strpos($phoneCode, '+') === 0) ? $phoneCode : '+' . ltrim($phoneCode, '+');
            @endphp
            PhoneCodes.initPhoneCodeForm({
                selectId: 'phone_code_select',
                helpTextId: 'phone_help_text',
                defaultCode: '{{ $phoneCode }}',
                apiUrl: '{{ route('dashboard.phone-codes') }}'
            });

            // Image upload preview for Logo Light
            if (typeof setupImageUpload === 'function') {
                setupImageUpload('uploadDivLight', 'logoLightInput', 'previewImgLight', 'uploadTextLight');

                // Image upload preview for Logo Dark
                setupImageUpload('uploadDivDark', 'logoDarkInput', 'previewImgDark', 'uploadTextDark');
            }
        });
    </script>
@endpush
