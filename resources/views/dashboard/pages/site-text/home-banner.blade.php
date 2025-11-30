@extends('dashboard.layouts.app')

@section('pageTitle', 'بانر الرئيسية')

@section('content')
    <div class="inner-body user_information">
        <form id="homeBannerForm" action="{{ route('dashboard.site-text.update', 'home_banner') }}" method="POST"
            enctype="multipart/form-data" autocomplete="off">
            @csrf
            @method('POST')
            <input type="hidden" name="type" value="home_banner">

            <div class="row">
                <!-- Image Light -->
                <div class="col-lg-6 col-md-6 col-sm-12 mt-3">
                    <div class="div_input_label">
                        <div class="uplode_section">
                            <div class="upload_div" id="uploadDivLight">
                                <img id="previewImgLight" src="{{ getFileFullUrl($siteText->image_light ?? null, null, 'public', 'white_img.png') }}" alt="Image Light">
                                <span id="uploadTextLight">صورة فاتحة</span>
                            </div>
                            <input type="file"
                                   id="imageLightInput"
                                   name="image_light"
                                   accept="{{ \App\Enums\MimesValidationEnums\ImageMimesValidationEnum::asRuleString() }}"
                                   style="display:none;">
                        </div>
                    </div>
                </div>

                <!-- Image Dark -->
                <div class="col-lg-6 col-md-6 col-sm-12 mt-3">
                    <div class="div_input_label">
                        <div class="uplode_section">
                            <div class="upload_div" id="uploadDivDark">
                                <img id="previewImgDark" src="{{ getFileFullUrl($siteText->image_dark ?? null, null, 'public', 'white_img.png') }}" alt="Image Dark">
                                <span id="uploadTextDark">صورة داكنة</span>
                            </div>
                            <input type="file"
                                   id="imageDarkInput"
                                   name="image_dark"
                                   accept="{{ \App\Enums\MimesValidationEnums\ImageMimesValidationEnum::asRuleString() }}"
                                   style="display:none;">
                        </div>
                    </div>
                </div>

                <!-- Title -->
                <div class="col-lg-12 col-md-12 col-sm-12 mt-3">
                    <div class="div_input_label">
                        <label class="label_style" for="title">العنوان <span class="text-danger">*</span></label>
                        <input type="text" id="title" name="title" class="form-control"
                            value="{{ old('title', $siteText->title ?? '') }}" placeholder="أدخل العنوان">
                    </div>
                </div>

                <!-- Description -->
                <div class="col-lg-12 col-md-12 col-sm-12 mt-3">
                    <div class="div_input_label">
                        <label class="label_style" for="description">الوصف <span class="text-danger">*</span></label>
                        <textarea id="description" name="description" class="form-control" rows="6" placeholder="أدخل الوصف">{{ old('description', $siteText->description ?? '') }}</textarea>
                    </div>
                </div>
            </div>

            <button type="submit" class="save_informations" id="submitBtn">
                <img src="{{ asset('assets/dashboard/images/correct_wihte.png') }}" alt="Icon">
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
                handleFormSubmission('#homeBannerForm', {
                    successMessage: 'تم حفظ البيانات بنجاح',
                    errorMessage: 'حدث خطأ أثناء حفظ البيانات',
                    afterSubmit: function(response) {
                        $('#homeBannerForm').find('.is-invalid').removeClass('is-invalid');
                        $('#homeBannerForm').find('.error-container').remove();
                    }
                });
            } else {
                console.error('ajax-handler.js is not loaded. Please ensure the script is included.');
            }

            // Image upload preview for Image Light
            if (typeof setupImageUpload === 'function') {
                setupImageUpload('uploadDivLight', 'imageLightInput', 'previewImgLight', 'uploadTextLight');

                // Image upload preview for Image Dark
                setupImageUpload('uploadDivDark', 'imageDarkInput', 'previewImgDark', 'uploadTextDark');
            }
        });
    </script>
@endpush
