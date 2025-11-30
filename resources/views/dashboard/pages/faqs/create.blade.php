@extends('dashboard.layouts.app')

@section('pageTitle', 'إضافة سؤال شائع')

@section('content')
    <div class="inner-body user_information">
        <form id="createFaqForm" action="{{ route('dashboard.faqs.store') }}" method="POST" autocomplete="off">
            @csrf

            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 mt-3">
                    <div class="div_input_label">
                        <label class="label_style" for="question">السؤال <span class="text-danger">*</span></label>
                        <input type="text"
                            id="question"
                            name="question"
                            class="form-control"
                            value="{{ old('question') }}"
                            placeholder="اكتب السؤال بشكل واضح">
                        @error('question')
                            <small class="text-danger d-block mt-1">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

                <div class="col-lg-12 col-md-12 col-sm-12 mt-3">
                    <div class="div_input_label">
                        <label class="label_style" for="answer">الإجابة <span class="text-danger">*</span></label>
                        <textarea id="answer"
                            name="answer"
                            class="form-control editor"
                            rows="6"
                            placeholder="اكتب الإجابة بالتفصيل">{{ old('answer') }}</textarea>
                        @error('answer')
                            <small class="text-danger d-block mt-1">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

                <div class="col-lg-12 col-md-12 col-sm-12 mt-3">
                    <div class="div_input_label">
                        <div class="form-check form-switch">
                            <input class="form-check-input check_styles" type="checkbox"
                                role="switch"
                                id="is_active"
                                name="is_active"
                                value="1"
                                {{ old('is_active', true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">
                                تفعيل السؤال مباشرة
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <button type="submit" class="save_informations" id="submitBtn">
                <img src="{{ asset('assets/dashboard/images/correct_wihte.png') }}" alt="Icon">
                <span class="button-text">حفظ السؤال</span>
            </button>
        </form>
    </div>
@endsection

@push('js')
    <script>
        $(document).ready(function() {
            if (typeof handleFormSubmission === 'function') {
                handleFormSubmission('#createFaqForm', {
                    successMessage: 'تم إضافة السؤال بنجاح',
                    errorMessage: 'حدث خطأ أثناء إضافة السؤال',
                    redirectUrl: '{{ route('dashboard.faqs.index') }}',
                    redirectImmediately: true
                });
            } else {
                console.error('ajax-handler.js لم يتم تحميله. يرجى التأكد من تضمين الملف.');
            }
        });
    </script>
@endpush
