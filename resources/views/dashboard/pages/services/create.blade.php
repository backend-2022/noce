@extends('dashboard.layouts.app')

@section('pageTitle', 'إضافة خدمة جديدة')

@section('content')
    <div class="inner-body user_information">
        <form id="createServiceForm" action="{{ route('dashboard.services.store') }}" method="POST" autocomplete="off">
            @csrf

            <div class="row">
                <!-- Name -->
                <div class="col-lg-12 col-md-12 col-sm-12 mt-3">
                    <div class="div_input_label">
                        <label class="label_style" for="name">اسم الخدمة <span class="text-danger">*</span></label>
                        <input type="text" id="name" name="name"
                            class="form-control" value="{{ old('name') }}"
                            placeholder="أدخل اسم الخدمة"
                            autocomplete="off"
                            data-lpignore="true">
                        @error('name')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Description -->
                <div class="col-lg-12 col-md-12 col-sm-12 mt-3">
                    <div class="div_input_label">
                        <label class="label_style" for="description">الوصف</label>
                        <textarea id="description" name="description"
                            class="form-control" rows="5"
                            placeholder="أدخل وصف الخدمة"
                            autocomplete="off"
                            data-lpignore="true">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Is Active -->
                <div class="col-lg-12 col-md-12 col-sm-12 mt-3">
                    <div class="div_input_label">
                        <div class="form-check form-switch">
                            <input class="form-check-input check_styles" type="checkbox" role="switch" id="is_active" name="is_active"
                                value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">
                                تفعيل الخدمة
                            </label>
                        </div>
                    </div>
                </div>

            </div>

            <button type="submit" class="save_informations" id="submitBtn">
                <img src="{{ asset('assets/dashboard/images/correct_wihte.png') }}">
                <span class="button-text">حفظ الخدمة</span>
            </button>
        </form>
    </div>
@endsection

@push('js')
    <script>
        $(document).ready(function() {
            // Initialize form submission handler
            if (typeof handleFormSubmission === 'function') {
                handleFormSubmission('#createServiceForm', {
                    successMessage: 'تم إضافة الخدمة بنجاح',
                    errorMessage: 'حدث خطأ أثناء إضافة الخدمة',
                    redirectUrl: '{{ route('dashboard.services.index') }}',
                    redirectImmediately: true
                });
            }
        });
    </script>
@endpush
