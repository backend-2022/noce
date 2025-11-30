<?php

namespace App\Providers;


use Illuminate\Support\ServiceProvider;
use App\Repositories\Interfaces\SettingRepositoryInterface;
use App\Repositories\Eloquent\SettingRepository;
use App\Repositories\Interfaces\AdminRepositoryInterface;
use App\Repositories\Eloquent\AdminRepository;
use App\Repositories\Interfaces\CityRepositoryInterface;
use App\Repositories\Eloquent\CityRepository;
use App\Repositories\Interfaces\FreeDesignRepositoryInterface;
use App\Repositories\Eloquent\FreeDesignRepository;
use App\Repositories\Interfaces\ServiceRepositoryInterface;
use App\Repositories\Eloquent\ServiceRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(SettingRepositoryInterface::class, SettingRepository::class);
        $this->app->bind(AdminRepositoryInterface::class, AdminRepository::class);
        $this->app->bind(CityRepositoryInterface::class, CityRepository::class);
        $this->app->bind(FreeDesignRepositoryInterface::class, FreeDesignRepository::class);
        $this->app->bind(ServiceRepositoryInterface::class, ServiceRepository::class);
    }
}
