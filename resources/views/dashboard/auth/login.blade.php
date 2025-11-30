@extends('website.layouts.app')

@section('content')
    <section class="hero">
        <div class="hero-content fade-in">
            <div class="contetn_hero">
                <h1>تسجيل الدخول</h1>
                <p>مرحباً بك في لوحة التحكم</p>
            </div>

            <div class="contact-form">
                <form id="loginForm" action="{{ route('dashboard.login') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="email">البريد الإلكتروني</label>
                        <input type="text" name="email" id="email" placeholder="example@gmail.com">
                    </div>
                    <div class="form-group">
                        <label for="password">كلمة المرور</label>
                        <input type="password" name="password" id="password" placeholder="كلمة المرور">
                    </div>
                    <button type="submit" class="btn-submit">
                        <span class="button-text">تسجيل الدخول</span>
                    </button>
                </form>
            </div>
        </div>
    </section>
@endsection

@push('js')
    <script>
        $(document).ready(function() {
            // Initialize form submission handler
            if (typeof handleFormSubmission === 'function') {
                handleFormSubmission('#loginForm', {
                    successMessage: 'تم تسجيل الدخول بنجاح',
                    errorMessage: 'حدث خطأ أثناء تسجيل الدخول',
                    redirectUrl: '{{ route('dashboard.dashboard') }}',
                    redirectImmediately: true,
                });
            } else {
                console.error('ajax-handler.js is not loaded. Please ensure the script is included.');
            }
        });
    </script>
@endpush
