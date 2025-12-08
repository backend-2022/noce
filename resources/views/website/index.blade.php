@extends('website.layouts.app')

@section('content')
    <section class="hero">
        <div class="hero-content fade-in">
            <div class="contetn_hero">
                <h1>{{ setting('promotional_title') ?? 'صمم مساحتك معانا' }}</h1>
                <p>{{ setting('description') ?? 'نحول أحلامك إلى واقع من خلال تصميمات داخلية مبتكرة وعصرية' }}</p>
            </div>

            <div class="contact-form">
                <div class="success-message" id="successMessage">
                    تم إرسال طلبك بنجاح! سنتواصل معك قريباً
                </div>
                <form id="contactForm" action="{{ route('free-design.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label> الاسم كامل <span class="required">*</span></label>
                        <input type="text" name="name" placeholder="الاسم كامل">
                    </div>
                    <div class="form-group">
                        <label> البريد الألكتروني <span class="required">*</span></label>
                        <input type="email" name="email" placeholder=" exampel@gmail.com">
                    </div>
                    <div class="form-group">
                        <label> رقم الجوال <span class="required">*</span></label>
                        <input type="tel" name="phone" placeholder=" 05*********">
                    </div>
                    <div class="form-group">
                        <label>اختر المدينه <span class="required">*</span></label>
                        <select name="city_id">
                            <option value="">اختر المدينه </option>
                            @foreach ($cities as $city)
                                <option value="{{ $city->id }}">{{ $city->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>اختر الخدمة <span class="required">*</span></label>
                        <select name="service_id">
                            <option value="">نوع الخدمة</option>
                            @foreach ($services as $service)
                                <option value="{{ $service->id }}">{{ $service->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit" class="btn-submit"> احصل علي تصميمك المجاني الأن</button>
                </form>
            </div>
        </div>
    </section>

    @if(setting('map_link'))
    <section class="map-section">
        <div class="map-container">
            <h2 class="map-title">موقعنا على الخريطة</h2>
            <div class="map-wrapper">
                <iframe
                    src="{{ setting('map_link') }}"
                    width="100%"
                    height="450"
                    style="border:0;"
                    allowfullscreen=""
                    loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade">
                </iframe>
            </div>
        </div>
    </section>
    @endif
@endsection

@push('js')
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
            // Contact Form Handler using ajax-handler
            if (typeof handleFormSubmission !== 'undefined') {
                const submitButton = document.querySelector('#contactForm button[type="submit"]');

                // Store original button text for handleFormSubmission
                if (submitButton && !submitButton.dataset.originalHtml) {
                    submitButton.dataset.originalHtml = submitButton.innerHTML;
                }

                handleFormSubmission('#contactForm', {
                    successMessage: 'تم إرسال طلبك بنجاح! سنتواصل معك قريباً',
                    errorMessage: 'حدث خطأ أثناء إرسال الطلب. يرجى المحاولة مرة أخرى.',
                    showSuccessToast: false, // Don't show toastr success message
                    beforeSubmit: function() {
                        // Show loading state for button
                        if (submitButton) {
                            submitButton.disabled = true;
                            submitButton.innerHTML = 'جاري الإرسال...';
                            submitButton.style.opacity = '0.7';
                            submitButton.style.cursor = 'not-allowed';
                        }
                        return true;
                    },
                    afterSubmit: function(response) {
                        const form = document.getElementById('contactForm');
                        const $form = $(form);

                        // Show custom success message element (above the form)
                        const successMessage = document.getElementById('successMessage');
                        if (successMessage) {
                            successMessage.classList.add('show');
                            setTimeout(() => {
                                successMessage.classList.remove('show');
                            }, 5000);
                        }

                        // Reset form and all fields
                        if (form) {
                            form.reset();
                        }

                        // Clear all input fields explicitly
                        $form.find('input[type="text"]').val('');
                        $form.find('input[type="email"]').val('');
                        $form.find('input[type="tel"]').val('');

                        // Reset all select dropdowns to default (first option)
                        $form.find('select').each(function() {
                            $(this).val('').trigger('change');
                        });

                        // Clear any remaining validation errors
                        $form.find('.is-invalid').removeClass('is-invalid');
                        $form.find('.error-container').remove();
                    }
                });

                // Clear validation errors when user interacts with fields
                const contactForm = document.getElementById('contactForm');
                if (contactForm && typeof $ !== 'undefined') {
                    $(contactForm).find('input, select, textarea').on('input change', function() {
                        $(this).removeClass('is-invalid');
                        $(this).closest('.form-group').find('.error-container').remove();
                    });
                }
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
@endpush
