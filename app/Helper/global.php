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

function convertGoogleMapsLinkToEmbed(?string $url): ?string
{
    if (empty($url)) {
        return null;
    }

    // If it's already an embed link, return as is
    if (str_contains($url, 'google.com/maps/embed')) {
        return $url;
    }

    // Handle maps.app.goo.gl share links - convert to embed format
    if (str_contains($url, 'maps.app.goo.gl')) {
        // For share links, return the original URL - JavaScript will handle the conversion
        // This ensures the location is preserved correctly
        return $url;
    }

    // Handle maps.google.com links with place ID
    if (preg_match('/maps\.google\.com\/maps\/.*place\/([^\/\?]+)/', $url, $matches)) {
        $placeId = $matches[1];
        return "https://www.google.com/maps?q=place_id:" . urlencode($placeId) . "&output=embed";
    }

    // Handle maps.google.com links with coordinates (@lat,lng)
    if (preg_match('/@(-?\d+\.?\d*),(-?\d+\.?\d*)/', $url, $matches)) {
        $lat = $matches[1];
        $lng = $matches[2];
        return "https://www.google.com/maps?q=" . $lat . "," . $lng . "&output=embed";
    }

    // For other Google Maps URLs, try to convert to embed format
    if (str_contains($url, 'google.com/maps')) {
        return "https://www.google.com/maps?q=" . urlencode($url) . "&output=embed";
    }

    // If it's not a Google Maps URL, return as is (might be a custom embed)
    return $url;
}
