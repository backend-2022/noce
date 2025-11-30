@extends('dashboard.layouts.app')

@section('pageTitle', 'كيف جدولها يشتغل؟')

@section('content')
    <div class="inner-body user_information">
        @for ($i = 1; $i <= 3; $i++)
            @php
                $item = $items[$i] ?? null;
                $formId = 'howWorkForm' . $i;
            @endphp
            <div class="mb-5">
                <h3 class="mb-3">الميزة {{ $i == 1 ? 'الأولى' : ($i == 2 ? 'الثانية' : 'الثالثة') }}</h3>

                <form id="{{ $formId }}" action="{{ route('dashboard.site-text.update', 'how_work') }}" method="POST"
                    enctype="multipart/form-data" autocomplete="off">
                    @csrf
                    @method('POST')
                    <input type="hidden" name="type" value="how_work">
                    <input type="hidden" name="order" value="{{ $i }}">
                    @if ($item && $item->id)
                        <input type="hidden" name="existing_id" value="{{ $item->id }}">
                    @endif

                    <div class="row">
                        <!-- Image Light -->
                        <div class="col-lg-6 col-md-6 col-sm-12 mt-3">
                            <div class="div_input_label">
                                <div class="uplode_section">
                                    <div class="upload_div" id="uploadDivLight{{ $i }}">
                                        <img id="previewImgLight{{ $i }}" src="{{ getFileFullUrl($item->image_light ?? null, null, 'public', 'white_img.png') }}"
                                            alt="Image Light">
                                        <span id="uploadTextLight{{ $i }}">صورة فاتحة</span>
                                    </div>
                                    <input type="file" id="imageLightInput{{ $i }}" name="image_light"
                                        accept="{{ \App\Enums\MimesValidationEnums\ImageMimesValidationEnum::asRuleString() }}"
                                        style="display:none;">
                                </div>
                            </div>
                        </div>

                        <!-- Image Dark -->
                        <div class="col-lg-6 col-md-6 col-sm-12 mt-3">
                            <div class="div_input_label">
                                <div class="uplode_section">
                                    <div class="upload_div" id="uploadDivDark{{ $i }}">
                                        <img id="previewImgDark{{ $i }}" src="{{ getFileFullUrl($item->image_dark ?? null, null, 'public', 'white_img.png') }}"
                                            alt="Image Dark">
                                        <span id="uploadTextDark{{ $i }}">صورة داكنة</span>
                                    </div>
                                    <input type="file" id="imageDarkInput{{ $i }}" name="image_dark"
                                        accept="{{ \App\Enums\MimesValidationEnums\ImageMimesValidationEnum::asRuleString() }}"
                                        style="display:none;">
                                </div>
                            </div>
                        </div>

                        <!-- Title -->
                        <div class="col-lg-12 col-md-12 col-sm-12 mt-3">
                            <div class="div_input_label">
                                <label class="label_style" for="title{{ $i }}">العنوان <span class="text-danger">*</span></label>
                                <input type="text" id="title{{ $i }}" name="title" class="form-control"
                                    value="{{ old('title', $item->title ?? '') }}" placeholder="أدخل العنوان">
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="col-lg-12 col-md-12 col-sm-12 mt-3">
                            <div class="div_input_label">
                                <label class="label_style" for="description{{ $i }}">الوصف <span class="text-danger">*</span></label>
                                <textarea id="description{{ $i }}" name="description" class="form-control" rows="6"
                                    placeholder="أدخل الوصف">{{ old('description', $item->description ?? '') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="save_informations" id="submitBtn{{ $i }}">
                        <img src="{{ asset('assets/dashboard/images/correct_wihte.png') }}" alt="Icon">
                        <span class="button-text">حفظ الميزة
                            {{ $i == 1 ? 'الأولى' : ($i == 2 ? 'الثانية' : 'الثالثة') }}</span>
                    </button>
                </form>
            </div>
        @endfor
    </div>
@endsection

@push('js')
    <script>
        $(document).ready(function() {
            @for ($i = 1; $i <= 3; $i++)
                // Initialize form submission handler for form {{ $i }}
                if (typeof handleFormSubmission === 'function') {
                    handleFormSubmission('#howWorkForm{{ $i }}', {
                        successMessage: 'تم حفظ الميزة {{ $i == 1 ? 'الأولى' : ($i == 2 ? 'الثانية' : 'الثالثة') }} بنجاح',
                        errorMessage: 'حدث خطأ أثناء حفظ البيانات',
                        afterSubmit: function(response) {
                            $('#howWorkForm{{ $i }}').find('.is-invalid').removeClass(
                                'is-invalid');
                            $('#howWorkForm{{ $i }}').find('.error-container').remove();

                            // Reapply image preview styling after successful submission
                            setTimeout(function() {
                                const previewImgLight = document.getElementById('previewImgLight{{ $i }}');
                                const previewImgDark = document.getElementById('previewImgDark{{ $i }}');
                                const uploadDivLight = document.getElementById('uploadDivLight{{ $i }}');
                                const uploadDivDark = document.getElementById('uploadDivDark{{ $i }}');
                                const uploadTextLight = document.getElementById('uploadTextLight{{ $i }}');
                                const uploadTextDark = document.getElementById('uploadTextDark{{ $i }}');

                                if (typeof applyImagePreviewStyling === 'function') {
                                    if (previewImgLight && uploadDivLight) {
                                        applyImagePreviewStyling(previewImgLight, uploadDivLight, uploadTextLight);
                                    }
                                    if (previewImgDark && uploadDivDark) {
                                        applyImagePreviewStyling(previewImgDark, uploadDivDark, uploadTextDark);
                                    }
                                }
                            }, 100);
                        }
                    });
                }

                // Image upload preview for Image Light {{ $i }}
                if (typeof setupImageUpload === 'function') {
                    setupImageUpload('uploadDivLight{{ $i }}', 'imageLightInput{{ $i }}',
                        'previewImgLight{{ $i }}', 'uploadTextLight{{ $i }}');
                    setupImageUpload('uploadDivDark{{ $i }}', 'imageDarkInput{{ $i }}',
                        'previewImgDark{{ $i }}', 'uploadTextDark{{ $i }}');
                }
            @endfor
        });
    </script>
@endpush
