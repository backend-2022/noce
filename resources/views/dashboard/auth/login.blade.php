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
                    <button type="submit" class="btn-submit" id="loginSubmitBtn">
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
            const loginForm = $('#loginForm');
            const emailInput = $('#email');
            const passwordInput = $('#password');
            const submitButton = $('#loginSubmitBtn');

            // Function to check if both fields are filled
            function checkFormValidity() {
                const emailValue = emailInput.val().trim();
                const passwordValue = passwordInput.val().trim();
                const bothFilled = emailValue !== '' && passwordValue !== '';

                // Update button state
                submitButton.prop('disabled', !bothFilled);
                if (bothFilled) {
                    submitButton.css({
                        'opacity': '1',
                        'cursor': 'pointer'
                    });
                } else {
                    submitButton.css({
                        'opacity': '0.6',
                        'cursor': 'not-allowed'
                    });
                }
            }

            // Set initial disabled state
            submitButton.prop('disabled', true);
            submitButton.css({
                'opacity': '0.6',
                'cursor': 'not-allowed'
            });

            // Listen to input events on both fields
            emailInput.on('input keyup', checkFormValidity);
            passwordInput.on('input keyup', checkFormValidity);

            // Prevent form submission if fields are not filled
            loginForm.on('submit', function(e) {
                const emailValue = emailInput.val().trim();
                const passwordValue = passwordInput.val().trim();

                if (emailValue === '' || passwordValue === '') {
                    e.preventDefault();
                    return false;
                }
            });

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
