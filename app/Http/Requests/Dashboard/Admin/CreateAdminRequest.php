<?php

namespace App\Http\Requests\Dashboard\Admin;

use Illuminate\Foundation\Http\FormRequest;
use App\Enums\InputsValidationEnums\InputEnum;
use App\Enums\MimesValidationEnums\ImageMimesValidationEnum;

class CreateAdminRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => InputEnum::TITLE->getValidationRules(). '|regex:/^[\p{Arabic}a-zA-Z0-9\s]+$/u', //  only letters, numbers and spaces and arabic letters and english letters
            'email' => InputEnum::EMAIL->getValidationRules() . '|unique:admins,email',
            'password' => InputEnum::PASSWORD->getValidationRules(),
            'password_confirmation' => InputEnum::PASSWORD->getValidationRules() . '|same:password',
            'image' => 'nullable|' . ImageMimesValidationEnum::validationRule(),
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

            'email.required' => 'حقل البريد الإلكتروني مطلوب.',
            'email.email' => 'البريد الإلكتروني يجب أن يكون صحيح.',
            'email.max' => 'البريد الإلكتروني لا يجب أن يتجاوز 255 حرف.',
            'email.regex' => 'البريد الإلكتروني يجب أن يكون بصيغة صحيحة مثل example@example.com',
            'email.unique' => 'البريد الإلكتروني مستخدم مسبقاً.',

            'password.required' => 'حقل كلمة المرور مطلوب.',
            'password.string' => 'كلمة المرور يجب أن تكون نص.',
            'password.min' => 'كلمة المرور يجب أن تكون على الأقل 8 أحرف.',
            'password.max' => 'كلمة المرور لا يجب أن تتجاوز 255 حرف.',
            'password.regex' => 'كلمة المرور يجب أن تحتوي على حرف كبير وحرف صغير ورقم ورمز خاص.',

            'password_confirmation.required' => 'حقل تأكيد كلمة المرور مطلوب.',
            'password_confirmation.string' => 'تأكيد كلمة المرور يجب أن يكون نص.',
            'password_confirmation.min' => 'تأكيد كلمة المرور يجب أن يكون على الأقل 8 أحرف.',
            'password_confirmation.max' => 'تأكيد كلمة المرور لا يجب أن يتجاوز 255 حرف.',
            'password_confirmation.regex' => 'تأكيد كلمة المرور يجب أن يحتوي على حرف كبير وحرف صغير ورقم ورمز خاص.',
            'password_confirmation.same' => 'تأكيد كلمة المرور يجب أن يطابق كلمة المرور.',

            'image.file' => 'الصورة يجب أن تكون ملف.',
            'image.mimes' => 'الصورة يجب أن تكون من نوع: ' . ImageMimesValidationEnum::asRuleString() . '.',
            'image.max' => 'حجم الصورة يجب أن لا يتجاوز 2 ميجابايت.',

            'is_active.boolean' => 'حالة النشاط يجب أن تكون صحيحة أو خاطئة.',
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'الاسم',
            'email' => 'البريد الإلكتروني',
            'password' => 'كلمة المرور',
            'password_confirmation' => 'تأكيد كلمة المرور',
            'image' => 'الصورة',
            'is_active' => 'حالة النشاط',
        ];
    }
}
