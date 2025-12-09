<?php

namespace App\Http\Requests\Dashboard\Setting;

use Illuminate\Foundation\Http\FormRequest;
use App\Enums\InputsValidationEnums\InputEnum;
use App\Enums\MimesValidationEnums\IconMimesValidationEnum;

class UpdateGeneralSettingRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        return [
            'site_name' => InputEnum::TITLE->getValidationRules(),
            'promotional_title' => InputEnum::TITLE->getValidationRules(),
            'description' => InputEnum::DESCRIPTION->getValidationRules(),
            'copyright_title' => InputEnum::TITLE->getValidationRules(),
            'footer_description' => InputEnum::DESCRIPTION->getValidationRules(false),
            'map_link' => 'nullable|string|max:10000', // Accept string to allow iframe code or URL
            'keep_backups' => 'nullable|in:1',
            'logo' => 'nullable|' . IconMimesValidationEnum::validationRule(),
            'home_banner' => 'nullable|' . IconMimesValidationEnum::validationRule(),
        ];
    }

    public function messages(): array
    {
        $logoMessages = IconMimesValidationEnum::getValidationMessages('logo');

        $homeBannerMessages = IconMimesValidationEnum::getValidationMessages('home_banner');

        return array_merge([
            'site_name.required' => 'اسم الموقع مطلوب',
            'site_name.string' => 'اسم الموقع يجب أن يكون نص',
            'site_name.max' => 'اسم الموقع يجب أن لا يتجاوز 255 حرف',
            'site_name.min' => 'اسم الموقع يجب أن يكون على الأقل 3 أحرف',

            'promotional_title.required' => 'العنوان الترويجي مطلوب',
            'promotional_title.string' => 'العنوان الترويجي يجب أن يكون نص',
            'promotional_title.max' => 'العنوان الترويجي يجب أن لا يتجاوز 255 حرف',
            'promotional_title.min' => 'العنوان الترويجي يجب أن يكون على الأقل 3 أحرف',

            'description.required' => 'نبذة عن الموقع مطلوب',
            'description.string' => 'نبذة عن الموقع يجب أن يكون نص',
            'description.max' => 'نبذة عن الموقع يجب أن لا يتجاوز 1000 حرف',
            'description.min' => 'نبذة عن الموقع يجب أن يكون على الأقل 3 أحرف',

            'copyright_title.required' => 'عنوان حقوق الملكية مطلوب',
            'copyright_title.string' => 'عنوان حقوق الملكية يجب أن يكون نص',
            'copyright_title.max' => 'عنوان حقوق الملكية يجب أن لا يتجاوز 255 حرف',
            'copyright_title.min' => 'عنوان حقوق الملكية يجب أن يكون على الأقل 3 أحرف',

            'footer_description.string' => 'وصف ال footer يجب أن يكون نص',
            'footer_description.max' => 'وصف ال footer يجب أن لا يتجاوز 1000 حرف',
            'footer_description.min' => 'وصف ال footer يجب أن يكون على الأقل 3 أحرف',

            'map_link.string' => 'رابط الخريطة يجب أن يكون نص',
            'map_link.max' => 'رابط الخريطة يجب أن لا يتجاوز 10000 حرف',
        ], $logoMessages, $homeBannerMessages);
    }


    public function attributes(): array
    {
        return [
            'site_name' => 'اسم الموقع',
            'promotional_title' => 'العنوان الترويجي',
            'description' => 'نبذة عن الموقع',
            'copyright_title' => 'عنوان حقوق الملكية',
            'footer_description' => 'وصف ال footer',
            'map_link' => 'رابط الخريطة',
            'logo' => 'الشعار',
            'home_banner' => 'صورة البانر الرئيسية',
        ];
    }
}
