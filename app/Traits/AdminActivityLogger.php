<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

trait AdminActivityLogger
{
    protected function logActivity(string $action, ?array $metadata = null): void
    {
        $admin = Auth::guard('admin')->user();

        if (!$admin) {
            return;
        }

        $logData = [
            'action' => $action,
            'performed_by_id' => $admin->id,
            'performed_by_email' => $admin->email,
            'performed_by_ip' => $this->getClientIp(),
            'metadata' => $metadata ?? [],
            'timestamp' => now()->toIso8601String(),
        ];

        Log::channel('admin_activity')->info('Admin Activity', $logData);
    }

    protected function getClientIp(): string
    {
        $request = request();

        // Check for IP in various headers (for proxies/load balancers)
        $ipHeaders = [
            'HTTP_CF_CONNECTING_IP',     // Cloudflare
            'HTTP_X_FORWARDED_FOR',      // Standard proxy header
            'HTTP_X_REAL_IP',            // Nginx proxy
            'HTTP_X_FORWARDED',          // Another proxy header
            'HTTP_X_CLUSTER_CLIENT_IP',  // Cluster
            'HTTP_FORWARDED_FOR',        // Proxy
            'HTTP_FORWARDED',            // Proxy
        ];

        foreach ($ipHeaders as $header) {
            $ip = $request->server($header);
            if ($ip) {
                // X-Forwarded-For can contain multiple IPs, get the first one
                $ips = explode(',', $ip);
                $ip = trim($ips[0]);

                // Validate IP address (allow private ranges for local development)
                if (filter_var($ip, FILTER_VALIDATE_IP)) {
                    return $ip;
                }
            }
        }

        // Try REMOTE_ADDR directly
        $ip = $request->server('REMOTE_ADDR');
        if ($ip && filter_var($ip, FILTER_VALIDATE_IP)) {
            return $ip;
        }

        // Fallback to Laravel's ip() method
        $ip = $request->ip();

        return $ip ?: '127.0.0.1';
    }
}
