<?php

namespace App\Http\Requests\Dashboard\Setting;

use Illuminate\Foundation\Http\FormRequest;
use App\Enums\InputsValidationEnums\InputEnum;

class UpdateSeoSettingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // SEO
            'meta_title' => InputEnum::TITLE->getValidationRules(false),
            'meta_keywords' => InputEnum::TEXTAREA->getValidationRules(false),
            'meta_description' => InputEnum::DESCRIPTION->getValidationRules(false),
        ];
    }

    public function messages(): array
    {
        return [
            'meta_title.string' => 'عنوان الميتا يجب أن يكون نص',
            'meta_title.min' => 'عنوان الميتا يجب أن يكون على الأقل 3 أحرف',
            'meta_title.max' => 'عنوان الميتا يجب أن لا يتجاوز 255 حرف',

            'meta_keywords.string' => 'الكلمات المفتاحية يجب أن تكون نص',
            'meta_keywords.min' => 'الكلمات المفتاحية يجب أن تكون على الأقل 3 أحرف',
            'meta_keywords.max' => 'الكلمات المفتاحية يجب أن لا تتجاوز 1000 حرف',

            'meta_description.string' => 'وصف الميتا يجب أن يكون نص',
            'meta_description.min' => 'وصف الميتا يجب أن يكون على الأقل 3 أحرف',
            'meta_description.max' => 'وصف الميتا يجب أن لا يتجاوز 500 حرف',
        ];
    }

    public function attributes(): array
    {
        return [
            'meta_title' => 'عنوان الميتا',
            'meta_keywords' => 'الكلمات المفتاحية',
            'meta_description' => 'وصف الميتا',
        ];
    }
}
