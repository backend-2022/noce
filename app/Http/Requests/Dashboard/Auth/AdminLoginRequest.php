<?php

namespace App\Http\Requests\Dashboard\Auth;

use Illuminate\Foundation\Http\FormRequest;
use App\Enums\InputsValidationEnums\InputEnum;
class AdminLoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => InputEnum::EMAIL->getValidationRules(true) . '|regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/',
            'password' => 'required|min:6',
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'حقل البريد الإلكتروني مطلوب.',
            'email.email' => 'البريد الإلكتروني يجب أن يكون صحيح.',
            'email.max' => 'البريد الإلكتروني لا يجب أن يتجاوز 255 حرف.',
            'email.regex' => 'يجب أن يكون بصيغة صحيحة example@example.com',
            'password.required' => 'حقل كلمة المرور مطلوب.',
            'password.min' => 'كلمة المرور يجب أن تكون على الأقل 6 أحرف.',
        ];
    }
}
