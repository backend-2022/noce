<?php

namespace App\Http\Requests\Dashboard\SiteText;

use Illuminate\Foundation\Http\FormRequest;
use App\Enums\MimesValidationEnums\ImageMimesValidationEnum;
use App\Enums\SiteTextEnums\SiteTextEnum;
use App\Enums\InputsValidationEnums\InputEnum;
use App\Repositories\Interfaces\SiteTextRepositoryInterface;

class SiteTextRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    protected function getMultiRecordTypes(): array
    {
        return [
            SiteTextEnum::HOW_WORK,
            SiteTextEnum::FEATURES,
            SiteTextEnum::INCREASE_PROFITS,
        ];
    }

    protected function supportsMultipleRecords(SiteTextEnum $type): bool
    {
        return in_array($type, $this->getMultiRecordTypes(), true);
    }

    protected function getRequiredFieldsConfig(SiteTextEnum $type): array
    {
        return[
                'title' => true,
                'description' => true,
                'image_light' => true,
                'image_dark' => false,
        ];
    }

    public function rules(): array
    {
        $type = SiteTextEnum::from($this->input('type'));
        $siteTextRepository = app(SiteTextRepositoryInterface::class);

        $requiredFields = $this->getRequiredFieldsConfig($type);

        $isMultiRecordType = $this->supportsMultipleRecords($type);

        if ($isMultiRecordType && $this->has('existing_id')) {
            $existingId = $this->input('existing_id');
            $allItems = $siteTextRepository->findAllByType($type);
            $existingSiteText = $allItems->where('id', $existingId)->first();
        } else {
            $existingSiteText = $siteTextRepository->findByType($type);
        }

        $imageLightRequired = $requiredFields['image_light'] && (!$existingSiteText || !$existingSiteText->image_light);
        $imageDarkRequired = $requiredFields['image_dark'] && (!$existingSiteText || !$existingSiteText->image_dark);

        return [
            'title' => InputEnum::TITLE->getValidationRules($requiredFields['title'] ?? false),
            'description' => InputEnum::DESCRIPTION->getValidationRules($requiredFields['description'] ?? false),
            'image_light' => ($imageLightRequired ? 'required|' : 'nullable|') . ImageMimesValidationEnum::validationRule(),
            'image_dark' => ($imageDarkRequired ? 'required|' : 'nullable|') . ImageMimesValidationEnum::validationRule(),
            'type' => 'required|in:'.SiteTextEnum::asRuleString(),
            'order' => 'nullable|integer|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'حقل العنوان مطلوب.',
            'title.string' => 'العنوان يجب أن يكون نص.',
            'title.min' => 'العنوان يجب أن يكون على الأقل 3 أحرف.',
            'title.max' => 'العنوان لا يجب أن يتجاوز 255 حرف.',
            'description.required' => 'حقل الوصف مطلوب.',
            'description.string' => 'الوصف يجب أن يكون نص.',
            'description.min' => 'الوصف يجب أن يكون على الأقل 3 أحرف.',
            'description.max' => 'الوصف لا يجب أن يتجاوز 1000 حرف.',
            'image_light.required' => 'حقل صورة الوضع الفاتح مطلوب.',
            'image_light.image' => 'صورة الوضع الفاتح يجب أن تكون صورة.',
            'image_light.mimes' => 'صورة الوضع الفاتح يجب أن تكون من نوع: ' . ImageMimesValidationEnum::asRuleString(),
            'image_light.max' => 'صورة الوضع الفاتح لا يجب أن يتجاوز 2048 ميجابايت.',
            'image_dark.required' => 'حقل صورة الوضع الداكن مطلوب.',
            'image_dark.image' => 'صورة الوضع الداكن يجب أن تكون صورة.',
            'image_dark.mimes' => 'صورة الوضع الداكن يجب أن تكون من نوع: ' . ImageMimesValidationEnum::asRuleString(),
            'image_dark.max' => 'صورة الوضع الداكن لا يجب أن يتجاوز 2048 ميجابايت.',
        ];
    }
}
