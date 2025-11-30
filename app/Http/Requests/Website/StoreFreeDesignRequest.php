<?php

namespace App\Http\Requests\Website;

use Illuminate\Foundation\Http\FormRequest;
use App\Enums\InputsValidationEnums\InputEnum;
use Illuminate\Validation\Rule;

class StoreFreeDesignRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => InputEnum::TITLE->getValidationRules(true),
            'email' => InputEnum::EMAIL->getValidationRules(true),
            'phone' => 'required|string|regex:/^(0)?5\d{8}$/',
            'city_id' => [
                'nullable',
                'integer',
                Rule::exists('cities', 'id')->where(function ($query) {
                    $query->where('is_active', true);
                }),
            ],
            'service_id' => [
                'nullable',
                'integer',
                Rule::exists('services', 'id')->where(function ($query) {
                    $query->where('is_active', true);
                }),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'حقل الاسم مطلوب.',
            'name.string' => 'الاسم يجب أن يكون نص.',
            'name.min' => 'الاسم يجب أن يكون على الأقل 3 أحرف.',
            'name.max' => 'الاسم يجب ألا يتجاوز 255 حرف.',

            'email.required' => 'حقل البريد الإلكتروني مطلوب.',
            'email.email' => 'البريد الإلكتروني يجب أن يكون صحيح.',
            'email.max' => 'البريد الإلكتروني لا يجب أن يتجاوز 255 حرف.',
            'email.regex' => 'البريد الإلكتروني غير صحيح.',

            'phone.required' => 'حقل رقم الجوال مطلوب.',
            'phone.string' => 'رقم الجوال يجب أن يكون نص.',
            'phone.regex' => 'رقم الجوال يجب أن يكون بصيغة 05xxxxxxxx أو 5xxxxxxxx.',

            'city_id.integer' => 'المدينة المختارة غير صحيحة.',
            'city_id.exists' => 'المدينة المختارة غير موجودة أو غير نشطة.',

            'service_id.integer' => 'الخدمة المختارة غير صحيحة.',
            'service_id.exists' => 'الخدمة المختارة غير موجودة أو غير نشطة.',
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'الاسم',
            'email' => 'البريد الإلكتروني',
            'phone' => 'رقم الجوال',
            'city_id' => 'المدينة',
            'service_id' => 'الخدمة',
        ];
    }
}

