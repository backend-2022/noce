<script>
    // Contact Form Handler
    document.getElementById('contactForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = {
            name: this.name.value,
            email: this.email.value,
            phone: this.phone.value,
            service: this.service.value
        };

        console.log('Contact Form Data:', formData);

        document.getElementById('successMessage').classList.add('show');
        this.reset();

        setTimeout(() => {
            document.getElementById('successMessage').classList.remove('show');
        }, 5000);
    });

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
