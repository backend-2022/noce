<?php

namespace App\Providers;


use Illuminate\Support\ServiceProvider;
use App\Repositories\Interfaces\SiteTextRepositoryInterface;
use App\Repositories\Eloquent\SiteTextRepository;
use App\Repositories\Interfaces\SettingRepositoryInterface;
use App\Repositories\Eloquent\SettingRepository;
use App\Repositories\Interfaces\AdminRepositoryInterface;
use App\Repositories\Eloquent\AdminRepository;
use App\Repositories\Interfaces\CityRepositoryInterface;
use App\Repositories\Eloquent\CityRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(SiteTextRepositoryInterface::class, SiteTextRepository::class);
        $this->app->bind(SettingRepositoryInterface::class, SettingRepository::class);
        $this->app->bind(AdminRepositoryInterface::class, AdminRepository::class);
        $this->app->bind(CityRepositoryInterface::class, CityRepository::class);
    }
}
