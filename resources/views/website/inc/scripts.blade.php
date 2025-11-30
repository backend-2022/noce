<!-- jQuery -->
<script src="{{ asset('assets/dashboard/js/jquery-3.5.1.min.js') }}?v={{ file_exists(public_path('assets/dashboard/js/jquery-3.5.1.min.js')) ? filemtime(public_path('assets/dashboard/js/jquery-3.5.1.min.js')) : time() }}"></script>
<!-- Toastr JS -->
<script src="{{ asset('assets/dashboard/js/toastr.min.js') }}?v={{ file_exists(public_path('assets/dashboard/js/toastr.min.js')) ? filemtime(public_path('assets/dashboard/js/toastr.min.js')) : time() }}"></script>
<!-- ajax-handler.js -->
<script src="{{ asset('assets/dashboard/js/ajax-handler.js') }}?v={{ file_exists(public_path('assets/dashboard/js/ajax-handler.js')) ? filemtime(public_path('assets/dashboard/js/ajax-handler.js')) : time() }}" data-cfasync="false"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Toastr configuration after scripts are loaded
        if (typeof toastr !== 'undefined') {
            toastr.options = {
                closeButton: true,
                debug: false,
                newestOnTop: true,
                progressBar: true,
                positionClass: 'toast-top-right',
                preventDuplicates: false,
                onclick: null,
                showDuration: '300',
                hideDuration: '1000',
                timeOut: '5000',
                extendedTimeOut: '1000',
                showEasing: 'swing',
                hideEasing: 'linear',
                showMethod: 'fadeIn',
                hideMethod: 'fadeOut',
                rtl: true
            };
        }
        // Contact Form Handler with custom error handling using toastr
        const contactForm = document.getElementById('contactForm');
        if (contactForm && typeof $ !== 'undefined' && typeof ajaxRequest !== 'undefined') {
            contactForm.addEventListener('submit', function(e) {
                e.preventDefault();

                const form = $(this);
                const formData = new FormData(this);
                const submitButton = form.find('button[type="submit"]');
                const originalButtonText = submitButton.html();

                // Show loading state
                submitButton.prop('disabled', true);
                submitButton.html('جاري الإرسال...');

                // Clear previous validation errors
                form.find('.is-invalid').removeClass('is-invalid');
                form.find('.error-container').remove();

                ajaxRequest({
                    url: form.attr('action'),
                    type: form.attr('method'),
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.success) {
                            // Show success toast
                            if (typeof toastr !== 'undefined') {
                                toastr.success(response.message || 'تم إرسال طلبك بنجاح! سنتواصل معك قريباً', 'تم بنجاح', {
                                    timeOut: 3000,
                                    rtl: true
                                });
                            }

                            // Show custom success message element
                            const successMessage = document.getElementById('successMessage');
                            if (successMessage) {
                                successMessage.classList.add('show');
                                setTimeout(() => {
                                    successMessage.classList.remove('show');
                                }, 5000);
                            }

                            // Reset form
                            contactForm[0].reset();

                            // Clear all form data
                            form[0].reset();

                            // Clear all input fields explicitly
                            form.find('input[type="text"]').val('');
                            form.find('input[type="email"]').val('');
                            form.find('input[type="tel"]').val('');

                            // Reset all select dropdowns to first option
                            form.find('select').each(function() {
                                $(this).val('').trigger('change');
                            });

                            // Clear any remaining validation errors
                            form.find('.is-invalid').removeClass('is-invalid');
                            form.find('.error-container').remove();
                        }
                    },
                    error: function(xhr) {
                        // Handle validation errors (422)
                        if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                            const errors = xhr.responseJSON.errors;
                            let errorMessages = [];

                            // Collect all error messages
                            Object.keys(errors).forEach(function(field) {
                                if (Array.isArray(errors[field])) {
                                    errors[field].forEach(function(message) {
                                        errorMessages.push(message);
                                    });
                                } else {
                                    errorMessages.push(errors[field]);
                                }

                                // Mark field as invalid
                                const input = form.find('[name="' + field + '"]');
                                if (input.length) {
                                    input.addClass('is-invalid');

                                    // Add error message below field
                                    const errorContainer = $('<div class="error-container"><span class="error-message text-danger" style="font-size:0.875rem;">' +
                                        (Array.isArray(errors[field]) ? errors[field][0] : errors[field]) +
                                        '</span></div>');
                                    input.closest('.form-group').append(errorContainer);
                                }
                            });

                            // Show all errors in toastr
                            if (errorMessages.length > 0) {
                                const errorText = errorMessages.join('<br>');
                                console.log('Showing validation errors:', errorText);
                                console.log('Toastr available:', typeof toastr !== 'undefined');

                                // Always try to show in toastr first
                                if (typeof toastr !== 'undefined' && typeof toastr.error === 'function') {
                                    toastr.error(errorText, 'خطأ في التحقق', {
                                        timeOut: 5000,
                                        extendedTimeOut: 1000,
                                        closeButton: true,
                                        rtl: true
                                    });
                                } else if (typeof showErrorAlert !== 'undefined' && typeof showErrorAlert === 'function') {
                                    // Fallback to showErrorAlert from ajax-handler.js
                                    showErrorAlert(errorText, null, 'خطأ في التحقق');
                                } else {
                                    // Last resort: use alert
                                    console.error('Toastr not available, using alert');
                                    alert('خطأ في التحقق:\n' + errorMessages.join('\n'));
                                }
                            }
                        } else {
                            // Handle other errors
                            const errorMessage = xhr.responseJSON?.message ||
                                                xhr.responseJSON?.error ||
                                                'حدث خطأ أثناء إرسال الطلب. يرجى المحاولة مرة أخرى.';

                            console.log('Showing error:', errorMessage);
                            console.log('Toastr available:', typeof toastr !== 'undefined');

                            // Always try to show in toastr first
                            if (typeof toastr !== 'undefined' && typeof toastr.error === 'function') {
                                toastr.error(errorMessage, 'خطأ', {
                                    timeOut: 5000,
                                    extendedTimeOut: 1000,
                                    closeButton: true,
                                    rtl: true
                                });
                            } else if (typeof showErrorAlert !== 'undefined' && typeof showErrorAlert === 'function') {
                                // Fallback to showErrorAlert from ajax-handler.js
                                showErrorAlert(errorMessage);
                            } else {
                                // Last resort: use alert
                                console.error('Toastr not available, using alert');
                                alert(errorMessage);
                            }
                        }
                    },
                    complete: function() {
                        // Re-enable button
                        submitButton.prop('disabled', false);
                        submitButton.html(originalButtonText);
                    }
                });
            });

            // Clear validation errors when user interacts with fields
            contactForm.find('input, select, textarea').on('input change', function() {
                $(this).removeClass('is-invalid');
                $(this).closest('.form-group').find('.error-container').remove();
            });
        }

        // Smooth Scroll for internal links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Scroll Animation
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -100px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);

        document.querySelectorAll('.feature-card, .service-card').forEach(el => {
            el.style.opacity = '0';
            el.style.transform = 'translateY(30px)';
            el.style.transition = 'opacity 0.6s, transform 0.6s';
            observer.observe(el);
        });

        // Stats Counter Animation
        const statsObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const target = entry.target;
                    const text = target.textContent;
                    const number = parseInt(text.replace(/[^0-9]/g, ''));
                    const symbol = text.replace(/[0-9]/g, '');

                    let current = 0;
                    const increment = number / 50;
                    const timer = setInterval(() => {
                        current += increment;
                        if (current >= number) {
                            target.textContent = symbol + number;
                            clearInterval(timer);
                        } else {
                            target.textContent = symbol + Math.floor(current);
                        }
                    }, 30);

                    statsObserver.unobserve(target);
                }
            });
        }, {
            threshold: 0.5
        });

        document.querySelectorAll('.stat-item h3').forEach(stat => {
            statsObserver.observe(stat);
        });
    });
</script>
@stack('js')
