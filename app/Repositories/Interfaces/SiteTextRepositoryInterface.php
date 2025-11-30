<?php

namespace App\Repositories\Interfaces;

use App\Models\SiteText;
use App\Enums\SiteTextEnums\SiteTextEnum;
use Illuminate\Database\Eloquent\Collection;

interface SiteTextRepositoryInterface
{
    public function findByType(SiteTextEnum $type): ?SiteText;
    public function findAllByType(SiteTextEnum $type): Collection;
    public function updateOrCreate(array $conditions, array $data): SiteText;
    public function all(): Collection;
    public function create(array $data): SiteText;
    public function update(SiteText $siteText, array $data): bool;
    public function delete(SiteText $siteText): bool;
}
