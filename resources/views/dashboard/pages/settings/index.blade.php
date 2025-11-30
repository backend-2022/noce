@extends('dashboard.layouts.app')

@section('content')
    <div class="inner-body user_information">
        <div class="contentt mb-4">
            <a class="ref_settings active" href="{{ route('dashboard.settings.index') }}">عام</a>
            <a class="ref_settings" href="{{ route('dashboard.settings.social-media.index') }}">روابط التواصل الاجتماعي</a>
        </div>

        <form id="settingsForm" action="{{ route('dashboard.settings.update') }}" method="POST" autocomplete="off">
            @csrf
            @method('PUT')

            <div class="row">
                <!-- Site Name -->
                <div class="col-lg-12 col-md-12 col-sm-12 mt-3">
                    <div class="div_input_label">
                        <label class="label_style" for="site_name">اسم الموقع</label>
                        <input type="text"
                               id="site_name"
                               name="site_name"
                               class="form-control"
                               value="{{ old('site_name', $settings['site_name'] ?? '') }}"
                               placeholder="اسم الموقع">
                    </div>
                </div>

                <!-- Promotional Title -->
                <div class="col-lg-12 col-md-12 col-sm-12 mt-3">
                    <div class="div_input_label">
                        <label class="label_style" for="promotional_title">العنوان الترويجي</label>
                        <input type="text"
                               id="promotional_title"
                               name="promotional_title"
                               class="form-control"
                               value="{{ old('promotional_title', $settings['promotional_title'] ?? '') }}"
                               placeholder="العنوان الترويجي">
                    </div>
                </div>

                <!-- Description -->
                <div class="col-lg-12 col-md-12 col-sm-12 mt-3">
                    <div class="div_input_label">
                        <label class="label_style" for="description">نبذة عن الموقع</label>
                        <textarea id="description"
                                  name="description"
                                  class="form-control"
                                  rows="4"
                                  placeholder="وصف">{{ old('description', $settings['description'] ?? '') }}</textarea>
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
        });
    </script>
@endpush
