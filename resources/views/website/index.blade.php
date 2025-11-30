@extends('website.layouts.app')

@section('content')
    <section class="hero">
        <div class="hero-content fade-in">
            <div class="contetn_hero">
                <h1>صمم مساحتك معانا</h1>
                <p>نحول أحلامك إلى واقع من خلال تصميمات داخلية مبتكرة وعصرية</p>
            </div>

            <div class="contact-form">
                <div class="success-message" id="successMessage">
                    تم إرسال طلبك بنجاح! سنتواصل معك قريباً
                </div>
                <form id="contactForm">
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
                        <select name="service" required>
                            <option value="">اختر المدينه </option>
                            <option value="residential"> جدة </option>
                            <option value="commercial"> الرياض</option>
                            <option value="office"> القصيم</option>
                            <option value="consultation"> المدينة المنورة </option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>اختر الخدمة</label>

                        <select name="service" required>
                            <option value="">نوع الخدمة</option>
                            <option value="residential">تصميم سكني</option>
                            <option value="commercial">تصميم تجاري</option>
                            <option value="office">تصميم مكاتب</option>
                            <option value="consultation">استشارة</option>
                        </select>
                    </div>

                    <button type="submit" class="btn-submit"> احصل علي تصميمك المجاني الأن</button>
                </form>
            </div>
        </div>
    </section>
@endsection
