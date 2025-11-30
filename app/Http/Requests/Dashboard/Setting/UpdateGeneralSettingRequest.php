<?php

namespace App\Http\Requests\Dashboard\Setting;

use Illuminate\Foundation\Http\FormRequest;
use App\Enums\MimesValidationEnums\IconMimesValidationEnum;
use App\Enums\InputsValidationEnums\InputEnum;
use App\Repositories\Interfaces\SettingRepositoryInterface;

class UpdateGeneralSettingRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        $settingRepository = app(SettingRepositoryInterface::class);

        // Check if logos already exist in database
        $logoLightExists = $settingRepository->getByKey('logo_light')?->value;
        $logoDarkExists = $settingRepository->getByKey('logo_dark')?->value;

        // Make required only if logo doesn't exist in database or if file is being uploaded
        $logoLightRule = ($logoLightExists && !$this->hasFile('logo_light'))
            ? 'nullable|' . IconMimesValidationEnum::validationRule()
            : 'required|' . IconMimesValidationEnum::validationRule();

        $logoDarkRule = ($logoDarkExists && !$this->hasFile('logo_dark'))
            ? 'nullable|' . IconMimesValidationEnum::validationRule()
            : 'required|' . IconMimesValidationEnum::validationRule();

        return [
            'title' => InputEnum::TITLE->getValidationRules(),
            'logo_light' => $logoLightRule,
            'logo_dark' => $logoDarkRule,
            'phone' => InputEnum::PHONE->getValidationRules(),
            'about' => InputEnum::DESCRIPTION->getValidationRules(),
            'whatsapp' =>  InputEnum::PHONE->getValidationRules(),
            'email' => InputEnum::EMAIL->getValidationRules(),
            'terms_and_conditions' => InputEnum::TEXTAREA->getValidationRules(),
            'privacy_policy' => InputEnum::TEXTAREA->getValidationRules(),
            'extension_url' => InputEnum::URL->getValidationRules(),
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'عنوان الموقع مطلوب',
            'title.string' => 'عنوان الموقع يجب أن يكون نص',
            'title.max' => 'عنوان الموقع يجب أن لا يتجاوز 255 حرف',
            'title.min' => 'عنوان الموقع يجب أن يكون على الأقل 3 أحرف',

            'logo_light.image' => 'اللوجو الفاتح يجب أن يكون صورة',
            'logo_light.mimes' => 'اللوجو الفاتح يجب أن يكون من نوع: ' . IconMimesValidationEnum::asRuleString().'.',
            'logo_light.max' => 'حجم اللوجو الفاتح يجب أن لا يتجاوز 2 ميجابايت',
            'logo_light.required' => 'اللوجو الفاتح مطلوب',

            'logo_dark.image' => 'اللوجو الداكن يجب أن يكون صورة',
            'logo_dark.mimes' => 'اللوجو الداكن يجب أن يكون من نوع: ' . IconMimesValidationEnum::asRuleString().'.',
            'logo_dark.max' => 'حجم اللوجو الداكن يجب أن لا يتجاوز 2 ميجابايت',
            'logo_dark.required' => 'اللوجو الداكن مطلوب',

            'phone.required' => 'رقم الهاتف مطلوب',
            'phone.string' => 'رقم الهاتف يجب أن يكون نص',
            'phone.size' => 'رقم الهاتف يجب أن يكون 9 أرقام بالضبط',
            'phone.regex' => 'رقم الهاتف يجب أن يبدأ بـ 5 ويحتوي على 9 أرقام فقط',

            'about.required' => 'نبذة عن الموقع مطلوبة',
            'about.string' => 'نبذة عن الموقع يجب أن تكون نص',
            'about.max' => 'نبذة عن الموقع يجب أن لا تتجاوز 1000 حرف',
            'about.min' => 'نبذة عن الموقع يجب أن يكون على الأقل 3 أحرف',

            'whatsapp.required' => 'رقم الواتساب مطلوب',
            'whatsapp.string' => 'رقم الواتساب يجب أن يكون نص',
            'whatsapp.size' => 'رقم الواتساب يجب أن يكون 9 أرقام بالضبط',
            'whatsapp.regex' => 'رقم الواتساب يجب أن يبدأ بـ 5 ويحتوي على 9 أرقام فقط',

            'email.required' => 'البريد الإلكتروني مطلوب',
            'email.email' => 'البريد الإلكتروني يجب أن يكون صحيح',
            'email.max' => 'البريد الإلكتروني يجب أن لا يتجاوز 255 حرف',
            'email.regex' => 'البريد الإلكتروني يجب أن يكون مثال example@example.com',
            
            'terms_and_conditions.required' => 'الشروط والأحكام مطلوبة',
            'terms_and_conditions.string' => 'الشروط والأحكام يجب أن تكون نص',
            'terms_and_conditions.max' => 'الشروط والأحكام يجب أن لا تتجاوز 10000 حرف',
            'terms_and_conditions.min' => 'الشروط والأحكام يجب أن يكون 10 أحرف على الأقل',

            'privacy_policy.required' => 'سياسة الخصوصية مطلوبة',
            'privacy_policy.string' => 'سياسة الخصوصية يجب أن تكون نص',
            'privacy_policy.max' => 'سياسة الخصوصية يجب أن لا تتجاوز 10000 حرف',
            'privacy_policy.min' => 'سياسة الخصوصية يجب أن يكون 10 أحرف على الأقل',

            'extension_url.url' => 'رابط الإضافة يجب أن يكون رابط صحيح',
            'extension_url.required' => 'رابط الإضافة مطلوب',
            'extension_url.max' => 'رابط الإضافة يجب أن لا يتجاوز 255 حرف',
            'extension_url.regex' => 'رابط الإضافة يجب أن يكون مثال https://example.com',


        ];
    }


    public function attributes(): array
    {
        return [
            'title' => 'عنوان الموقع',
            'logo_light' => 'اللوجو الفاتح',
            'logo_dark' => 'اللوجو الداكن',
            'phone_code' => 'رمز الهاتف',
            'phone' => 'رقم الهاتف',
            'about' => 'نبذة عن الموقع',
            'whatsapp' => 'رقم الواتساب',
            'email' => 'البريد الإلكتروني',
            'terms_and_conditions' => 'الشروط والأحكام',
            'privacy_policy' => 'سياسة الخصوصية',
            'extension_url' => 'رابط الإضافة',
        ];
    }
}
