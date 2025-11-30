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
            'twitter' => InputEnum::URL->getValidationRules(false),
            'facebook' => InputEnum::URL->getValidationRules(false),
            'instagram' => InputEnum::URL->getValidationRules(false),
            'snapchat' => InputEnum::URL->getValidationRules(false),
            'tiktok' => InputEnum::URL->getValidationRules(false),
        ];
    }

    public function messages(): array
    {
        return [
            'twitter.url' => 'رابط تويتر يجب أن يكون رابط صحيح',
            'twitter.max' => 'رابط تويتر يجب أن لا يتجاوز 255 حرف',


            'facebook.url' => 'رابط فيسبوك يجب أن يكون رابط صحيح',
            'facebook.max' => 'رابط فيسبوك يجب أن لا يتجاوز 255 حرف',

            'instagram.url' => 'رابط انستجرام يجب أن يكون رابط صحيح',
            'instagram.max' => 'رابط انستجرام يجب أن لا يتجاوز 255 حرف',

            'snapchat.url' => 'رابط سناب شات يجب أن يكون رابط صحيح',
            'snapchat.max' => 'رابط سناب شات يجب أن لا يتجاوز 255 حرف',

            'tiktok.url' => 'رابط التيك توك يجب أن يكون رابط صحيح',
            'tiktok.max' => 'رابط التيك توك يجب أن لا يتجاوز 255 حرف',
        ];
    }

    public function attributes(): array
    {
        return [
            'twitter' => 'رابط تويتر',
            'facebook' => 'رابط فيسبوك',
            'instagram' => 'رابط انستجرام',
            'snapchat' => 'رابط سناب شات',
            'tiktok' => 'رابط التيك توك',
        ];
    }
}
