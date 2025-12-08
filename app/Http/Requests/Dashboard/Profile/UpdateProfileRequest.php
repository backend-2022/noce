<?php

namespace App\Http\Requests\Dashboard\Profile;

use Illuminate\Foundation\Http\FormRequest;
use App\Enums\InputsValidationEnums\InputEnum;
use App\Enums\MimesValidationEnums\ImageMimesValidationEnum;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Contracts\Validation\Validator;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $adminId = auth('admin')->id();

        return [
            'name' => InputEnum::TITLE->getValidationRules(). '|regex:/^[\p{Arabic}a-zA-Z0-9\s]+$/u', //  only letters, numbers and spaces and arabic letters and english letters
            'email' => InputEnum::EMAIL->getValidationRules(true, [Rule::unique('admins', 'email')->ignore($adminId)]),
            'image' => ImageMimesValidationEnum::validationRule(),
            'password' => InputEnum::PASSWORD->getValidationRules(false),
            'password_confirmation' => InputEnum::PASSWORD->getValidationRules(false) . '|same:password|required_with:password',
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function ($validator) {
            $admin = auth('admin')->user();
            $newPassword = $this->input('password');

            if ($newPassword && $admin && Hash::check($newPassword, $admin->password)) {
                $validator->errors()->add('password', 'كلمة المرور الجديدة يجب أن تكون مختلفة عن كلمة المرور القديمة.');
            }
        });
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
            'email.email' => 'البريد الإلكتروني يجب أن يكون صحيحاً.',
            'email.regex' => 'يجب ان في صيغة example@mail.com',
            'email.unique' => 'البريد الإلكتروني مستخدم بالفعل.',
            'email.max' => 'البريد الإلكتروني لا يجب أن يتجاوز 255 حرف.',

            'image.image' => 'يجب أن تكون صورة.',
            'image.mimes' => 'يجب أن تكون من نوع: ' . ImageMimesValidationEnum::asRuleString(),
            'image.max' => 'حجم الصورة لا يجب أن يتجاوز ' . ImageMimesValidationEnum::MAX_IMAGE_SIZE_KB . ' كيلوبايت.',

            'password.min' => 'كلمة المرور يجب أن تكون على الأقل 8 أحرف.',
            'password.regex' => 'كلمة المرور يجب أن تحتوي على حرف كبير، وحرف صغير، ورقم، ورمز خاص (مثل: @, #, $, !).',
            'password.max' => 'كلمة المرور لا يجب أن تتجاوز 255 حرف.',
            'password.different' => 'كلمة المرور الجديدة يجب أن تكون مختلفة عن كلمة المرور القديمة.',

            'password_confirmation.required_with' => 'تأكيد كلمة المرور مطلوب عند تغيير كلمة المرور.',
            'password_confirmation.same' => 'تأكيد كلمة المرور يجب أن يطابق كلمة المرور.',
            'password_confirmation.regex' => 'تأكيد كلمة المرور يجب أن يحتوي على حرف كبير، وحرف صغير، ورقم، ورمز خاص (مثل: @, #, $, !).',
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'الاسم',
            'email' => 'البريد الإلكتروني',
            'image' => 'الصورة',
            'password' => 'كلمة المرور',
            'password_confirmation' => 'تأكيد كلمة المرور',
        ];
    }
}
