<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Utm;
use Illuminate\Support\Facades\Log;

class CaptureUtmFromUrl
{
    public function handle(Request $request, Closure $next)
    {
        $utm_data = $request->only([
            'utm_source', 'utm_medium', 'utm_campaign', 'utm_id',
            'utm_ads_set_id', 'utm_ads_set_name', 'ad_name', 'ad_id'
        ]);

        // Check if any field contains template placeholder format {{...}}
        foreach ($utm_data as $value) {
            if (!empty($value) && preg_match('/\{\{.*\}\}/', $value)) {
            return $next($request);
        }
        }

        // Filter out empty values and check if we have any UTM data
        $filtered_data = array_filter($utm_data, function($value) {
            return !empty($value);
        });

        if (!empty($filtered_data)) {
            try {
            Utm::create([
                'utm_source' => $utm_data['utm_source'] ?? null,
                'utm_medium' => $utm_data['utm_medium'] ?? null,
                'utm_campaign' => $utm_data['utm_campaign'] ?? null,
                'utm_id' => $utm_data['utm_id'] ?? null,
                'utm_ads_set_id' => $utm_data['utm_ads_set_id'] ?? null,
                'utm_ads_set_name' => $utm_data['utm_ads_set_name'] ?? null,
                'ad_name' => $utm_data['ad_name'] ?? null,
                'ad_id' => $utm_data['ad_id'] ?? null,
            ]);
            } catch (\Exception $e) {
                Log::error('Failed to store UTM data', [
                    'utm_data' => $utm_data,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]);
            }
        }

        return $next($request);
    }
}
