@extends('dashboard.layouts.app')

@section('content')
    <div class="inner-body user_information">
        <div class="contentt mb-4">
            <a class="ref_settings active" href="{{ route('dashboard.settings.index') }}">عام</a>
            <a class="ref_settings" href="{{ route('dashboard.settings.social-media.index') }}">روابط التواصل الاجتماعي</a>
            <a class="ref_settings" href="{{ route('dashboard.settings.seo.index') }}">بيانات SEO</a>
        </div>

        <form id="settingsForm" action="{{ route('dashboard.settings.update') }}" method="POST" autocomplete="off"
            enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row">
                <!-- Site Name -->
                <div class="col-lg-12 col-md-12 col-sm-12 mt-3">
                    <div class="div_input_label">
                        <label class="label_style" for="site_name">اسم الموقع</label>
                        <input type="text" id="site_name" name="site_name" class="form-control"
                            value="{{ old('site_name', $settings['site_name'] ?? '') }}" placeholder="اسم الموقع">
                    </div>
                </div>

                <!-- Promotional Title -->
                <div class="col-lg-12 col-md-12 col-sm-12 mt-3">
                    <div class="div_input_label">
                        <label class="label_style" for="promotional_title">العنوان الترويجي</label>
                        <input type="text" id="promotional_title" name="promotional_title" class="form-control"
                            value="{{ old('promotional_title', $settings['promotional_title'] ?? '') }}"
                            placeholder="العنوان الترويجي">
                    </div>
                </div>

                <!-- Description -->
                <div class="col-lg-12 col-md-12 col-sm-12 mt-3">
                    <div class="div_input_label">
                        <label class="label_style" for="description">نبذة عن الموقع</label>
                        <textarea id="description" name="description" class="form-control" rows="4" placeholder="وصف">{{ old('description', $settings['description'] ?? '') }}</textarea>
                    </div>
                </div>

                <!-- Map Link -->
                <div class="col-lg-12 col-md-12 col-sm-12 mt-3">
                    <div class="div_input_label">
                        <label class="label_style" for="map_link">رابط الخريطة</label>
                        <textarea id="map_link" name="map_link" class="form-control" rows="3"
                            placeholder="يمكنك إدخال رابط الخريطة أو كود iframe كامل:
- رابط: https://www.google.com/maps/embed?pb=...
- أو iframe: &lt;iframe src=&quot;https://www.google.com/maps/embed?pb=...&quot;&gt;&lt;/iframe&gt;">{{ old('map_link', $settings['map_link'] ?? '') }}</textarea>
                        <small class="text-muted">يمكنك إدخال رابط الخريطة مباشرة أو كود iframe كامل من Google Maps</small>
                    </div>
                </div>

                <!-- Logo Upload -->
                <div class="col-lg-12 col-md-12 col-sm-12 mt-3">
                    <div class="div_input_label">
                        <label class="label_style" for="logo">الشعار</label>
                        <div class="uplode_section">
                            <div class="upload_div" id="logoUploadDiv">
                                @php
                                    $logoUrl = asset('assets/dashboard/images/white_img.png');
                                    if (isset($settings['logo']) && !empty($settings['logo'])) {
                                        $logoUrl = getFileFullUrl(
                                            $settings['logo'],
                                            null,
                                            'public',
                                            'white_img.png',
                                        );
                                    }
                                @endphp
                                <img id="logoPreviewImg" src="{{ $logoUrl }}" alt="Logo"
                                    onerror="this.src='{{ asset('assets/dashboard/images/white_img.png') }}'">
                                <span id="logoUploadText">إضغط لاختيار الشعار</span>
                            </div>
                            <input type="file" id="logoInput" name="logo" accept="image/*"
                                style="display:none;">
                        </div>
                    </div>
                </div>

                <!-- Home Banner Upload -->
                <div class="col-lg-12 col-md-12 col-sm-12 mt-3">
                    <div class="div_input_label">
                        <label class="label_style" for="home_banner">صورة خلفية الرئيسية</label>
                        <div class="uplode_section">
                            <div class="upload_div" id="homeBannerUploadDiv">
                                @php
                                    $homeBannerUrl = asset('assets/dashboard/images/white_img.png');
                                    if (isset($settings['home_banner']) && !empty($settings['home_banner'])) {
                                        $homeBannerUrl = getFileFullUrl(
                                            $settings['home_banner'],
                                            null,
                                            'public',
                                            'white_img.png',
                                        );
                                    }
                                @endphp
                                <img id="homeBannerPreviewImg" src="{{ $homeBannerUrl }}" alt="Home Banner"
                                    onerror="this.src='{{ asset('assets/dashboard/images/white_img.png') }}'">
                                <span id="homeBannerUploadText">إضغط لاختيار صورة خلفية الرئيسية</span>
                            </div>
                            <input type="file" id="homeBannerInput" name="home_banner" accept="image/*"
                                style="display:none;">
                        </div>
                    </div>
                </div>

                <!-- Keep Backups Toggle -->
                <div class="col-lg-12 col-md-12 col-sm-12 mt-3">
                    <div class="div_input_label">
                        <div class="form-check form-switch mt-2">
                            <input class="form-check-input check_styles rounded-pill" type="checkbox" role="switch"
                                name="keep_backups" value="1" id="keep_backups_toggle"
                                {{ old('keep_backups', $settings['keep_backups'] ?? '') == '1' ? 'checked' : '' }} />
                            <label class="form-check-label" for="keep_backups_toggle">
                                الاحتفاظ بالنسخ الاحتياطية القديمة
                            </label>
                        </div>
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
            // Initialize logo image upload
            if (typeof setupImageUpload === 'function') {
                setupImageUpload('logoUploadDiv', 'logoInput', 'logoPreviewImg', 'logoUploadText', 'white_img.png');
                setupImageUpload('homeBannerUploadDiv', 'homeBannerInput', 'homeBannerPreviewImg', 'homeBannerUploadText', 'white_img.png');
            }

            // Initialize form submission handler
            if (typeof handleFormSubmission === 'function') {
                handleFormSubmission('#settingsForm', {
                    successMessage: 'تم تحديث الإعدادات بنجاح',
                    errorMessage: 'حدث خطأ أثناء تحديث الإعدادات',
                    afterSubmit: function(response) {
                        // Clear any previous validation errors on success
                        $('#settingsForm').find('.is-invalid').removeClass('is-invalid');
                        $('#settingsForm').find('.error-container').remove();

                        // Update logo preview if logo was uploaded
                        if (response.data && response.data.settings && response.data.settings
                            .logo_url) {
                            $('#logoPreviewImg').attr('src', response.data.settings.logo_url);
                            if (typeof applyImagePreviewStyling === 'function') {
                                applyImagePreviewStyling(
                                    document.getElementById('logoPreviewImg'),
                                    document.getElementById('logoUploadDiv'),
                                    document.getElementById('logoUploadText'),
                                    'white_img.png'
                                );
                            }
                        }

                        // Update home_banner preview if home_banner was uploaded
                        if (response.data && response.data.settings && response.data.settings
                            .home_banner_url) {
                            $('#homeBannerPreviewImg').attr('src', response.data.settings.home_banner_url);
                            if (typeof applyImagePreviewStyling === 'function') {
                                applyImagePreviewStyling(
                                    document.getElementById('homeBannerPreviewImg'),
                                    document.getElementById('homeBannerUploadDiv'),
                                    document.getElementById('homeBannerUploadText'),
                                    'white_img.png'
                                );
                            }
                        }
                    }
                });
            } else {
                console.error('ajax-handler.js is not loaded. Please ensure the script is included.');
            }
        });
    </script>
@endpush
