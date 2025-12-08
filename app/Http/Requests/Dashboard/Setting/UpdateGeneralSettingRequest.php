<?php

namespace App\Http\Requests\Dashboard\Setting;

use Illuminate\Foundation\Http\FormRequest;
use App\Enums\InputsValidationEnums\InputEnum;

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
            'map_link' => InputEnum::URL->getValidationRules(false),
        ];
    }

    public function messages(): array
    {
        return [
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

            'map_link.url' => 'رابط الخريطة يجب أن يكون رابط صحيح',
            'map_link.max' => 'رابط الخريطة يجب أن لا يتجاوز 1000 حرف',
        ];
    }


    public function attributes(): array
    {
        return [
            'site_name' => 'اسم الموقع',
            'promotional_title' => 'العنوان الترويجي',
            'description' => 'نبذة عن الموقع',
            'map_link' => 'رابط الخريطة',
        ];
    }
}
