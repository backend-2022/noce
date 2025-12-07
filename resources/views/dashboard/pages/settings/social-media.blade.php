@extends('dashboard.layouts.app')

@section('content')
    <div class="inner-body user_information">
        <div class="contentt mb-4">
            <a class="ref_settings" href="{{ route('dashboard.settings.index') }}">عام</a>
            <a class="ref_settings active" href="{{ route('dashboard.settings.social-media.index') }}">روابط التواصل الاجتماعي</a>
            <a class="ref_settings" href="{{ route('dashboard.settings.seo.index') }}">بيانات SEO</a>
        </div>

        <form id="socialMediaForm" action="{{ route('dashboard.settings.social-media.update') }}" method="POST" autocomplete="off">
            @csrf
            @method('PUT')

            <div class="row">
                <!-- Facebook -->
                <div class="col-lg-6 col-md-6 col-sm-12 mt-3">
                    <div class="div_input_label">
                        <label class="label_style" for="facebook">رابط الفيس بوك</label>
                        <input type="text"
                               id="facebook"
                               name="facebook"
                               class="form-control"
                               value="{{ old('facebook', $settings['facebook'] ?? '') }}"
                               placeholder="https://www.facebook.com">
                    </div>
                </div>

                <!-- X (Twitter) -->
                <div class="col-lg-6 col-md-6 col-sm-12 mt-3">
                    <div class="div_input_label">
                        <label class="label_style" for="x">رابط X (تويتر)</label>
                        <input type="text"
                               id="x"
                               name="x"
                               class="form-control"
                               value="{{ old('x', $settings['x'] ?? '') }}"
                               placeholder="https://www.x.com">
                    </div>
                </div>

                <!-- Instagram -->
                <div class="col-lg-6 col-md-6 col-sm-12 mt-3">
                    <div class="div_input_label">
                        <label class="label_style" for="instagram">رابط انستجرام</label>
                        <input type="text"
                               id="instagram"
                               name="instagram"
                               class="form-control"
                               value="{{ old('instagram', $settings['instagram'] ?? '') }}"
                               placeholder="https://www.instagram.com">
                    </div>
                </div>

                <!-- Snapchat -->
                <div class="col-lg-6 col-md-6 col-sm-12 mt-3">
                    <div class="div_input_label">
                        <label class="label_style" for="snapchat">رابط سناب شات</label>
                        <input type="text"
                               id="snapchat"
                               name="snapchat"
                               class="form-control"
                               value="{{ old('snapchat', $settings['snapchat'] ?? '') }}"
                               placeholder="https://www.snapchat.com">
                    </div>
                </div>

                <!-- WhatsApp -->
                <div class="col-lg-6 col-md-6 col-sm-12 mt-3">
                    <div class="div_input_label">
                        <label class="label_style" for="whatsapp">رقم الواتساب</label>
                        <input type="text"
                               id="whatsapp"
                               name="whatsapp"
                               class="form-control"
                               value="{{ old('whatsapp', $settings['whatsapp'] ?? '') }}"
                               placeholder="5xxxxxxxx">
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
                handleFormSubmission('#socialMediaForm', {
                    successMessage: 'تم تحديث روابط التواصل الاجتماعي بنجاح',
                    errorMessage: 'حدث خطأ أثناء تحديث روابط التواصل الاجتماعي',
                    afterSubmit: function(response) {
                        // Clear any previous validation errors on success
                        $('#socialMediaForm').find('.is-invalid').removeClass('is-invalid');
                        $('#socialMediaForm').find('.error-container').remove();
                    }
                });
            } else {
                console.error('ajax-handler.js is not loaded. Please ensure the script is included.');
            }

            // Clear validation errors when user starts typing/changing inputs
            $('#socialMediaForm input').on('input change', function() {
                if (this.classList.contains('is-invalid')) {
                    this.classList.remove('is-invalid');
                    const errorContainer = $(this).closest('.div_input_label').find('.error-container');
                    if (errorContainer.length) {
                        errorContainer.remove();
                    }
                }
            });
        });
    </script>
@endpush
