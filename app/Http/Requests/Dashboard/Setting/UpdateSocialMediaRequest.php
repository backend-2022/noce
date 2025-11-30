<?php

namespace App\Http\Requests\Dashboard\Setting;

use Illuminate\Foundation\Http\FormRequest;
use App\Enums\InputsValidationEnums\InputEnum;

class UpdateSocialMediaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'facebook' => InputEnum::URL->getValidationRules(false),
            'x' => InputEnum::URL->getValidationRules(false),
            'instagram' => InputEnum::URL->getValidationRules(false),
            'snapchat' => InputEnum::URL->getValidationRules(false),
            'whatsapp' => InputEnum::PHONE->getValidationRules(false),
        ];
    }

    public function messages(): array
    {
        return [
            'facebook.url' => 'رابط فيسبوك يجب أن يكون رابط صحيح',
            'facebook.max' => 'رابط فيسبوك يجب أن لا يتجاوز 255 حرف',

            'x.url' => 'رابط X يجب أن يكون رابط صحيح',
            'x.max' => 'رابط X يجب أن لا يتجاوز 255 حرف',

            'instagram.url' => 'رابط انستجرام يجب أن يكون رابط صحيح',
            'instagram.max' => 'رابط انستجرام يجب أن لا يتجاوز 255 حرف',

            'snapchat.url' => 'رابط سناب شات يجب أن يكون رابط صحيح',
            'snapchat.max' => 'رابط سناب شات يجب أن لا يتجاوز 255 حرف',

            'whatsapp.required' => 'رقم الواتساب مطلوب',
            'whatsapp.string' => 'رقم الواتساب يجب أن يكون نص',
            'whatsapp.regex' => 'رقم الواتساب يجب أن يبدأ بـ 5 ويحتوي على 9 أرقام فقط',
        ];
    }

    public function attributes(): array
    {
        return [
            'facebook' => 'رابط فيسبوك',
            'x' => 'رابط X',
            'instagram' => 'رابط انستجرام',
            'snapchat' => 'رابط سناب شات',
            'whatsapp' => 'رقم الواتساب',
        ];
    }
}
