<?php

namespace App\Http\Requests\Dashboard\City;

use Illuminate\Foundation\Http\FormRequest;
use App\Enums\InputsValidationEnums\InputEnum;

class UpdateCityRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $cityId = $this->route('city')->id;

        return [
            'name' => InputEnum::TITLE->getValidationRules() .'|unique:cities,name,' . $cityId,
            'is_active' => 'boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'حقل الاسم مطلوب.',
            'name.string' => 'الاسم يجب أن يكون نص.',
            'name.min' => 'الاسم يجب أن يكون على الأقل 3 أحرف.',
            'name.max' => 'الاسم لا يجب أن يتجاوز 255 حرف.',
            'name.regex' => 'الاسم يجب أن يحتوي على حروف عربية أو إنجليزية وأرقام ومسافات فقط.',
            'name.unique' => 'اسم المدينة مستخدم مسبقاً.',

            'is_active.boolean' => 'حالة النشاط يجب أن تكون صحيحة أو خاطئة.',
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'الاسم',
            'is_active' => 'حالة النشاط',
        ];
    }
}
