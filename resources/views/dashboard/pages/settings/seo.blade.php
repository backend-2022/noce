@extends('dashboard.layouts.app')

@section('content')
    <div class="inner-body user_information">
        <div class="contentt mb-4">
            <a class="ref_settings" href="{{ route('dashboard.settings.index') }}">عام</a>
            <a class="ref_settings" href="{{ route('dashboard.settings.social-media.index') }}">روابط التواصل الاجتماعي</a>
            <a class="ref_settings active" href="{{ route('dashboard.settings.seo.index') }}">بيانات SEO</a>
        </div>

        <form id="seoForm" action="{{ route('dashboard.settings.seo.update') }}" method="POST" autocomplete="off">
            @csrf
            @method('PUT')

            <div class="row">
                <!-- Meta Title -->
                <div class="col-lg-12 col-md-12 col-sm-12 mt-3">
                    <div class="div_input_label">
                        <label class="label_style" for="meta_title">عنوان الميتا (Meta Title)</label>
                        <input type="text"
                               id="meta_title"
                               name="meta_title"
                               class="form-control"
                               value="{{ old('meta_title', $settings['meta_title'] ?? '') }}"
                               placeholder="عنوان الميتا">
                    </div>
                </div>

                <!-- Meta Description -->
                <div class="col-lg-12 col-md-12 col-sm-12 mt-3">
                    <div class="div_input_label">
                        <label class="label_style" for="meta_description">وصف الميتا (Meta Description)</label>
                        <textarea id="meta_description"
                                  name="meta_description"
                                  class="form-control"
                                  rows="4"
                                  placeholder="وصف الميتا">{{ old('meta_description', $settings['meta_description'] ?? '') }}</textarea>
                    </div>
                </div>

                <!-- Meta Keywords -->
                <div class="col-lg-12 col-md-12 col-sm-12 mt-3">
                    <div class="div_input_label">
                        <label class="label_style" for="meta_keywords">الكلمات المفتاحية (Meta Keywords)</label>
                        <input type="text"
                               id="meta_keywords"
                               name="meta_keywords"
                               class="form-control"
                               value="{{ old('meta_keywords', $settings['meta_keywords'] ?? '') }}"
                               placeholder="الكلمات المفتاحية (مفصولة بفواصل)">
                    </div>
                </div>
            </div>

                <button type="submit" class="save_informations" id="submitBtn">
                    <img src="{{ asset('assets/dashboard/images/correct_wihte.png') }}">
                    <span class="button-text">حفظ</span>
                </button>
        </form>
    </div>
@endsection

@push('js')
    <script>
        $(document).ready(function() {
            // Initialize form submission handler
            if (typeof handleFormSubmission === 'function') {
                handleFormSubmission('#seoForm', {
                    successMessage: 'تم تحديث بيانات SEO بنجاح',
                    errorMessage: 'حدث خطأ أثناء تحديث بيانات SEO',
                    afterSubmit: function(response) {
                        // Clear any previous validation errors on success
                        $('#seoForm').find('.is-invalid').removeClass('is-invalid');
                        $('#seoForm').find('.error-container').remove();

                        // Reload the page after successful update
                        setTimeout(function() {
                            window.location.reload();
                        }, 1000);
                    }
                });
            } else {
                console.error('ajax-handler.js is not loaded. Please ensure the script is included.');
            }

            // Clear validation errors when user starts typing/changing inputs
            $('#seoForm input, #seoForm textarea').on('input change', function() {
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
