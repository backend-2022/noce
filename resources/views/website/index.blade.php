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
                        <label> الاسم الكامل </label>
                        <input type="text" name="name" placeholder="الاسم الكامل" required>
                    </div>
                    <div class="form-group">
                        <label> البريد الألكتروني </label>
                        <input type="email" name="email" placeholder=" exampel@gmail.com" required>
                    </div>
                    <div class="form-group">
                        <label> رقم الجوال</label>
                        <input type="tel" name="phone" placeholder=" 05*********" required>
                    </div>
                    <div class="form-group">
                        <label>اختر المدينه </label>
                        <select name="city_id" required>
                            <option value="">اختر المدينه </option>
                            @foreach($cities as $city)
                                <option value="{{ $city->id }}">{{ $city->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>اختر الخدمة</label>
                        <select name="service_id" required>
                            <option value="">نوع الخدمة</option>
                            @foreach($services as $service)
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
