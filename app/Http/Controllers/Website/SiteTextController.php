<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\SiteTextRepositoryInterface;
use App\Enums\SiteTextEnums\SiteTextEnum;
use Illuminate\View\View;

class SiteTextController extends Controller
{
    protected $siteTextRepository;

    public function __construct(SiteTextRepositoryInterface $siteTextRepository)
    {
        $this->siteTextRepository = $siteTextRepository;
    }

    public function index(): View
    {
        $siteTexts = $this->getAll();

        $homeBanner = $siteTexts[SiteTextEnum::HOME_BANNER->value] ?? null;
        $howWork = $this->siteTextRepository->findAllByType(SiteTextEnum::HOW_WORK);
        $features = $this->siteTextRepository->findAllByType(SiteTextEnum::FEATURES);
        $increaseProfitsItems = $this->siteTextRepository->findAllByType(SiteTextEnum::INCREASE_PROFITS);

        return view('website.index', compact('homeBanner', 'howWork', 'features', 'increaseProfitsItems', 'siteTexts'));

    }

    public function getAll(): array
    {
        $siteTexts = [];

        foreach (SiteTextEnum::cases() as $type) {
            if ($type !== SiteTextEnum::HOW_WORK && $type !== SiteTextEnum::FEATURES && $type !== SiteTextEnum::INCREASE_PROFITS) {
                $siteTexts[$type->value] = $this->siteTextRepository->findByType($type);
            }
        }

        return $siteTexts;
    }

    public function getByType(SiteTextEnum $type)
    {
        return $this->siteTextRepository->findByType($type);
    }
}
