<?php

namespace App\Http\Requests\Dashboard\Service;

use Illuminate\Foundation\Http\FormRequest;
use App\Enums\InputsValidationEnums\InputEnum;

class CreateServiceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => InputEnum::TITLE->getValidationRules() . '|regex:/^[\p{Arabic}a-zA-Z0-9\s]+$/u|unique:services,name',
            'description' => InputEnum::DESCRIPTION->getValidationRules(false),
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
            'name.unique' => 'اسم الخدمة مستخدم مسبقاً.',

            'description.string' => 'الوصف يجب أن يكون نص.',
            'description.min' => 'الوصف يجب أن يكون على الأقل 3 أحرف.',
            'description.max' => 'الوصف لا يجب أن يتجاوز 1000 حرف.',

            'is_active.boolean' => 'حالة النشاط يجب أن تكون صحيحة أو خاطئة.',
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'الاسم',
            'description' => 'الوصف',
            'is_active' => 'حالة النشاط',
        ];
    }
}
