<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            // Logo Settings
            [
                'key' => 'logo_light',
                'value' => null,
            ],
            [
                'key' => 'logo_dark',
                'value' => null,
            ],

            // General Settings
            [
                'key' => 'title',
                'value' => 'جدولها - نظام إدارة الجداول',
            ],

            // Contact Settings
            [
                'key' => 'whatsapp',
                'value' => '966501234567',
            ],
            [
                'key' => 'phone_code',
                'value' => '966',
            ],
            [
                'key' => 'phone',
                'value' => '501234567',
            ],
            [
                'key' => 'email',
                'value' => 'info@jadwelha.com',
            ],

            // Social Media Settings
            [
                'key' => 'twitter',
                'value' => 'https://twitter.com/jadwelha',
            ],
            [
                'key' => 'facebook',
                'value' => 'https://facebook.com/jadwelha',
            ],
            [
                'key' => 'instagram',
                'value' => 'https://instagram.com/jadwelha',
            ],
            [
                'key' => 'snapchat',
                'value' => 'https://snapchat.com/add/jadwelha',
            ],
            [
                'key' => 'tiktok',
                'value' => 'https://tiktok.com/@jadwelha',
            ],

            // Content Settings
            [
                'key' => 'about',
                'value' => 'جدولها هو نظام متطور لإدارة الجداول والمواعيد، مصمم لتبسيط عملية تنظيم الوقت وإدارة المواعيد بكفاءة عالية. يوفر النظام إمكانيات متقدمة لإنشاء وإدارة الجداول الزمنية، مع واجهة سهلة الاستخدام وميزات ذكية تساعد في تحسين الإنتاجية وتنظيم العمل.',
            ],

            // Legal Settings
            [
                'key' => 'terms_and_conditions',
                'value' => 'شروط وأحكام استخدام موقع جدولها

1. القبول بالشروط
باستخدام موقع جدولها، فإنك توافق على الالتزام بهذه الشروط والأحكام.

2. الخدمات
نوفر خدمات إدارة الجداول والمواعيد للمستخدمين في المملكة العربية السعودية.

3. الاستخدام
- يجب التأكد من صحة البيانات المدخلة
- يمكن إدارة الجداول حسب سياسة الاستخدام
- الخدمة متاحة 24/7

4. المدفوعات
- نقبل جميع البطاقات الائتمانية الرئيسية
- المدفوعات آمنة ومشفرة
- يتم خصم المبلغ عند تأكيد الخدمة

5. المسؤولية
- لا نتحمل مسؤولية أي أضرار غير مباشرة
- نلتزم بتوفير الخدمة بأفضل جودة ممكنة

6. التعديلات
نحتفظ بحق تعديل هذه الشروط في أي وقت مع إشعار المستخدمين.',
            ],
            [
                'key' => 'privacy_policy',
                'value' => 'سياسة الخصوصية - موقع جدولها

1. جمع المعلومات
نقوم بجمع المعلومات التالية:
- الاسم ورقم الهاتف
- عنوان البريد الإلكتروني
- بيانات الجداول والمواعيد
- معلومات الدفع

2. استخدام المعلومات
نستخدم معلوماتك من أجل:
- إتمام عمليات إدارة الجداول
- التواصل معك بخصوص الخدمات
- إرسال التحديثات والإشعارات
- تحسين خدماتنا

3. حماية المعلومات
- نستخدم تقنيات تشفير متقدمة
- لا نشارك معلوماتك مع أطراف ثالثة
- نحتفظ بمعلوماتك بأمان تام

4. ملفات تعريف الارتباط
نستخدم ملفات تعريف الارتباط لتحسين تجربة التصفح وتقديم محتوى مخصص.

5. حقوقك
يمكنك:
- طلب نسخة من معلوماتك
- طلب حذف معلوماتك
- إلغاء الاشتراك في الرسائل التسويقية

6. التحديثات
نقوم بتحديث سياسة الخصوصية دورياً ونخطركم بأي تغييرات.',
            ],

            // Technical Settings
            [
                'key' => 'extension_url',
                'value' => 'https://chrome.google.com/webstore/detail/jadwelha',
            ],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}
