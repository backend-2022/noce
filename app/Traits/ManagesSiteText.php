<?php

namespace App\Traits;

use App\Enums\SiteTextEnums\SiteTextEnum;
use App\Repositories\Interfaces\SiteTextRepositoryInterface;
use Illuminate\View\View;

trait ManagesSiteText
{
    use ApiResponse;

    protected $siteTextRepository;

    public function __construct(SiteTextRepositoryInterface $siteTextRepository)
    {
        $this->siteTextRepository = $siteTextRepository;
    }

    protected function getSiteTextConfig(SiteTextEnum $type): array
    {
        $configs = [
            SiteTextEnum::HOME_BANNER->value => [
                'title' => SiteTextEnum::HOME_BANNER->label(),
                'has_titles' => true,
                'fixed_titles' => null,
                'image_light_label' => 'صورة فاتحة',
                'image_dark_label' => 'صورة داكنة',
                'required_fields' => [
                    'title' => true,
                    'description' => true,
                    'image_light' => true,
                    'image_dark' => true,
                ],
            ],
            SiteTextEnum::HOW_WORK->value => [
                'title' => SiteTextEnum::HOW_WORK->label(),
                'has_titles' => true,
                'fixed_titles' => null,
                'image_light_label' => 'صورة فاتحة',
                'image_dark_label' => 'صورة داكنة',
                'required_fields' => [
                    'title' => true,
                    'description' => true,
                    'image_light' => true,
                    'image_dark' => true,
                ],
            ],
            SiteTextEnum::FEATURES->value => [
                'title' => SiteTextEnum::FEATURES->label(),
                'has_titles' => true,
                'fixed_titles' => null,
                'image_light_label' => 'صورة فاتحة',
                'image_dark_label' => 'صورة داكنة',
                'required_fields' => [
                    'title' => true,
                    'description' => true,
                    'image_light' => true,
                    'image_dark' => true,
                ],
            ],
            SiteTextEnum::INCREASE_PROFITS->value => [
                'title' => SiteTextEnum::INCREASE_PROFITS->label(),
                'has_titles' => true,
                'fixed_titles' => null,
                'image_light_label' => 'صورة فاتحة',
                'image_dark_label' => 'صورة داكنة',
                'required_fields' => [
                    'title' => false,
                    'description' => false,
                    'image_light' => false,
                    'image_dark' => false,
                ],
            ],
        ];

        return $configs[$type->value] ?? [];
    }

    protected function supportsMultipleRecords(SiteTextEnum $type): bool
    {
        $multiRecordTypes = [
            SiteTextEnum::HOW_WORK,
            SiteTextEnum::FEATURES,
            SiteTextEnum::INCREASE_PROFITS,
        ];

        return in_array($type, $multiRecordTypes, true);
    }
    public function updateSiteText(SiteTextEnum $type, $request)
    {
        $data = $request->validated();
        $config = $this->getSiteTextConfig($type);

        if ($request->hasFile('image_light')) {
            $isMultiRecordType = $this->supportsMultipleRecords($type);

            if ($isMultiRecordType && $request->has('existing_id')) {
                $existingSiteText = $this->siteTextRepository->findAllByType($type)
                    ->where('id', $request->input('existing_id'))
                    ->first();
            } else {
                $existingSiteText = $this->siteTextRepository->findByType($type);
            }

            if ($existingSiteText && $existingSiteText->image_light) {
                $this->deleteFile($existingSiteText->image_light, null, 'public');
            }
            $imageFile = $request->file('image_light');
            $imagePath = $this->uploadFile($imageFile, null, 'site-texts', 'public');
            $data['image_light'] = $imagePath;
        }

        if ($request->hasFile('image_dark')) {
            $isMultiRecordType = $this->supportsMultipleRecords($type);

            if ($isMultiRecordType && $request->has('existing_id')) {
                $existingSiteText = $this->siteTextRepository->findAllByType($type)
                    ->where('id', $request->input('existing_id'))
                    ->first();
            } else {
                $existingSiteText = $this->siteTextRepository->findByType($type);
            }

            if ($existingSiteText && $existingSiteText->image_dark) {
                $this->deleteFile($existingSiteText->image_dark, null, 'public');
            }
            $imageFile = $request->file('image_dark');
            $imagePath = $this->uploadFile($imageFile, null, 'site-texts', 'public');
            $data['image_dark'] = $imagePath;
        }

        if ($config['has_titles']) {
            // titles from form
        } else {
            $data['title'] = $config['fixed_titles'][0];
        }

        $data['type'] = $type->value;

        if ($this->supportsMultipleRecords($type)) {
            $order = $request->input('order', 0);
            $data['order'] = $order;

            $this->siteTextRepository->updateOrCreate(
                [
                    'type' => $type->value,
                    'order' => $order
                ],
                $data
            );
        } else {
            $this->siteTextRepository->updateOrCreate(['type' => $type->value], $data);
        }

        if ($request->ajax()) {
            return $this->successResponse(message: 'تم الحفظ بنجاح');
        }

        return redirect()
            ->route('dashboard.site-text.show', $type->value)
            ->with('success', 'تم التحديث بنجاح.');
    }
}
