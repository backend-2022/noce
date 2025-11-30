<?php

namespace App\Http\Controllers\Dashboard\SiteText;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\SiteText\SiteTextRequest;
use App\Traits\ManagesSiteText;
use App\Traits\FileUploads;
use App\Enums\SiteTextEnums\SiteTextEnum;
use App\Repositories\Interfaces\SiteTextRepositoryInterface;
use Illuminate\View\View;

class SiteTextController extends Controller
{
    use ManagesSiteText, FileUploads;

    public function __construct(SiteTextRepositoryInterface $siteTextRepository)
    {
        $this->siteTextRepository = $siteTextRepository;
    }

    public function show(string $type): View
    {
        $siteTextType = SiteTextEnum::from($type);
        $config = $this->getSiteTextConfig($siteTextType);
        $type = $siteTextType;

        // Use dedicated views for specific types
        return match ($siteTextType) {
            SiteTextEnum::HOME_BANNER => $this->showHomeBanner($siteTextType, $config, $type),
            SiteTextEnum::HOW_WORK => $this->showHowWork($siteTextType, $config, $type),
            SiteTextEnum::FEATURES => $this->showFeatures($siteTextType, $config, $type),
            SiteTextEnum::INCREASE_PROFITS => $this->showIncreaseProfits($siteTextType, $config, $type),
        };
    }

    private function showHomeBanner(SiteTextEnum $siteTextType, array $config, SiteTextEnum $type): View
    {
        $siteText = $this->siteTextRepository->findByType($siteTextType);
        return view('dashboard.pages.site-text.home-banner', compact('siteText', 'config', 'type'));
    }

    private function showHowWork(SiteTextEnum $siteTextType, array $config, SiteTextEnum $type): View
    {
        $howWorkItems = $this->siteTextRepository->findAllByType($siteTextType);

        $items = [];
        for ($i = 1; $i <= 3; $i++) {
            $items[$i] = $howWorkItems->firstWhere('order', $i)
                ?? $howWorkItems->get($i - 1)
                ?? null;
        }

        return view('dashboard.pages.site-text.how-work', compact('items', 'config', 'type'));
    }

    private function showFeatures(SiteTextEnum $siteTextType, array $config, SiteTextEnum $type): View
    {
        $featuresItems = $this->siteTextRepository->findAllByType($siteTextType);

        $items = [];
        for ($i = 1; $i <= 4; $i++) {
            $items[$i] = $featuresItems->firstWhere('order', $i)
                ?? $featuresItems->get($i - 1)
                ?? null;
        }

        return view('dashboard.pages.site-text.features', compact('items', 'config', 'type'));
    }

    private function showIncreaseProfits(SiteTextEnum $siteTextType, array $config, SiteTextEnum $type): View
    {
        $increaseProfitsItems = $this->siteTextRepository->findAllByType($siteTextType);

        $items = [];
        for ($i = 1; $i <= 3; $i++) {
            $items[$i] = $increaseProfitsItems->firstWhere('order', $i)
                ?? $increaseProfitsItems->get($i - 1)
                ?? null;
        }

        return view('dashboard.pages.site-text.increase-profits', compact('items', 'config', 'type'));
    }

    public function update(string $type, SiteTextRequest $request)
    {
        $siteTextType = SiteTextEnum::from($type);
        return $this->updateSiteText($siteTextType, $request);
    }
}
