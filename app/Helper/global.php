<?php

function getFileFullUrl(?string $relativePath, ?int $id = null, string $disk = 'public', string $fallbackImage = 'no-picture.png'): string
{
    $manage_file = new class
    {
        use \App\Traits\FileUploads;
    };

    return $manage_file->getFileUrl(
        $relativePath,
        $id,
        $disk,
        $fallbackImage
    );
}

function isActiveRoute(string $routeName, string $class = 'active'): string
{
    $currentRoute = request()->route()?->getName();
    return $currentRoute === $routeName ? $class : '';
}

function isActiveRouteGroup(string $routeGroup, string $class = 'active'): string
{
    $currentRoute = request()->route()?->getName();
    if (!$currentRoute) {
        return '';
    }
    // Remove trailing dot if exists and add it for comparison
    $routeGroup = rtrim($routeGroup, '.') . '.';
    return str_starts_with($currentRoute, $routeGroup) ? $class : '';
}

function setting(string $key, ?string $default = null): ?string
{
    $settingRepository = app(\App\Repositories\Interfaces\SettingRepositoryInterface::class);
    $setting = $settingRepository->getByKey($key);
    return $setting?->value ?? $default;
}
