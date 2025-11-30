<script>
    // Contact Form Handler
    const contactForm = document.getElementById('contactForm');
    if (contactForm) {
        contactForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const submitButton = this.querySelector('button[type="submit"]');
            const originalButtonText = submitButton ? submitButton.innerHTML : '';
            
            // Disable submit button
            if (submitButton) {
                submitButton.disabled = true;
                submitButton.innerHTML = 'جاري الإرسال...';
            }

            // Get form data
            const formData = new FormData(this);

            // Send AJAX request
            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(async response => {
                const data = await response.json();
                
                if (!response.ok) {
                    // Handle validation errors
                    if (data.errors) {
                        let errorMessage = 'يرجى تصحيح الأخطاء التالية:\n';
                        Object.keys(data.errors).forEach(key => {
                            errorMessage += `- ${data.errors[key][0]}\n`;
                        });
                        alert(errorMessage);
                    } else {
                        alert(data.message || 'حدث خطأ أثناء إرسال الطلب');
                    }
                    return;
                }

                if (data.success) {
                    // Show success message
                    const successMessage = document.getElementById('successMessage');
                    if (successMessage) {
                        successMessage.classList.add('show');
                        setTimeout(() => {
                            successMessage.classList.remove('show');
                        }, 5000);
                    }
                    
                    // Reset form
                    this.reset();
                } else {
                    // Show error message
                    alert(data.message || 'حدث خطأ أثناء إرسال الطلب');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('حدث خطأ أثناء إرسال الطلب. يرجى المحاولة مرة أخرى.');
            })
            .finally(() => {
                // Re-enable submit button
                if (submitButton) {
                    submitButton.disabled = false;
                    submitButton.innerHTML = originalButtonText;
                }
            });
        });
    }

    // Appointment Form Handler
    document.getElementById('appointmentForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const appointmentData = {
            fullname: this.fullname.value,
            mobile: this.mobile.value,
            email: this.email2.value,
            date: this.date.value,
            time: this.time.value,
            notes: this.notes.value
        };

        console.log('Appointment Data:', appointmentData);

        document.getElementById('appointmentSuccess').classList.add('show');
        this.reset();

        setTimeout(() => {
            document.getElementById('appointmentSuccess').classList.remove('show');
        }, 5000);
    });

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
</script>
@stack('js')
