<?php

namespace App\Http\Controllers\Dashboard\ActivityLogs;

use App\Http\Controllers\Controller;
use App\Traits\AdminActivityLogger;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Admin;
use Illuminate\Support\Facades\Log;

class ActivityLogController extends Controller
{
    use ApiResponse, AdminActivityLogger;

    public function index(Request $request): View|JsonResponse
    {

        if ($request->expectsJson() || $request->ajax()) {
            return $this->getActivityLogsData($request);
        }

        $admins = Admin::select(['id', 'name', 'email'])
            ->orderBy('name')
            ->get();

        return view('dashboard.pages.activity-logs.index', compact('admins'));
    }

    public function getActivityLogsData(Request $request): JsonResponse
    {

        try {
            $logFile = storage_path('logs/admin-activity.log');

            if (!file_exists($logFile)) {
                return response()->json([
                    'draw' => (int) $request->input('draw', 1),
                    'recordsTotal' => 0,
                    'recordsFiltered' => 0,
                    'data' => [],
                ]);
            }

            $logs = $this->readLogsFromFile($logFile, 200);

            $filteredLogs = $this->applyFilters($logs, $request);

            $start = (int) $request->input('start', 0);
            $length = (int) $request->input('length', 10);
            $totalRecords = count($filteredLogs);

            $paginatedLogs = array_slice($filteredLogs, $start, $length);

            $data = array_map(function ($log, $index) use ($start) {
                return [
                    'DT_RowIndex' => $start + $index + 1,
                    'timestamp' => $this->formatTimestamp($log['timestamp'] ?? ''),
                    'admin_email' => $log['performed_by_email'] ?? 'غير محدد',
                    'ip_address' => $log['performed_by_ip'] ?? 'غير محدد',
                    'action' => $log['action'] ?? 'غير محدد',
                    'details' => $log['metadata'] ?? [],
                    'raw_data' => $log,
                ];
            }, $paginatedLogs, array_keys($paginatedLogs));

            return response()->json([
                'draw' => (int) $request->input('draw', 1),
                'recordsTotal' => $totalRecords,
                'recordsFiltered' => $totalRecords,
                'data' => $data,
            ]);
        } catch (\Exception $e) {
            \Log::error('Activity Log Error: ' . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'draw' => (int) $request->input('draw', 1),
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => [],
                'error' => 'حدث خطأ في تحميل سجل الأنشطة: ' . $e->getMessage(),
            ], 200);
        }
    }

    private function readLogsFromFile(string $filePath, int $maxLines = 200): array
    {
        $logs = [];

        if (!file_exists($filePath)) {
            return $logs;
        }

        if (!is_readable($filePath)) {
            \Log::warning('Activity log file is not readable: ' . $filePath);
            return $logs;
        }

        $lines = @file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        if ($lines === false || empty($lines)) {
            return $logs;
        }

        $lines = array_slice($lines, -$maxLines);
        $lines = array_reverse($lines);

        foreach ($lines as $line) {
            $parsedLog = $this->parseLogLine($line);
            if ($parsedLog) {
                $logs[] = $parsedLog;
            }
        }

        return $logs;
    }

    private function parseLogLine(string $line): ?array
    {
        if (preg_match('/\[(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})\].*?Admin Activity\s+(.*)$/', $line, $matches)) {
            $timestamp = $matches[1];
            $jsonData = trim($matches[2]);

            // Try to decode JSON
            $data = json_decode($jsonData, true);
            if ($data && is_array($data)) {
                // Convert timestamp to ISO format
                try {
                    $dateTime = \Carbon\Carbon::parse($timestamp);
                    $data['timestamp'] = $dateTime->toIso8601String();
                } catch (\Exception $e) {
                    $data['timestamp'] = $timestamp;
                }
                return $data;
            }
        }

        if (preg_match('/\{.*\}/', $line, $jsonMatches)) {
            $data = json_decode($jsonMatches[0], true);
            if ($data && is_array($data) && isset($data['action'])) {
                // Try to extract timestamp from line
                if (preg_match('/\[(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})\]/', $line, $timeMatches)) {
                    try {
                        $dateTime = \Carbon\Carbon::parse($timeMatches[1]);
                        $data['timestamp'] = $dateTime->toIso8601String();
                    } catch (\Exception $e) {
                        $data['timestamp'] = $timeMatches[1];
                    }
                } elseif (!isset($data['timestamp'])) {
                    $data['timestamp'] = now()->toIso8601String();
                }
                return $data;
            }
        }

        return null;
    }

    private function applyFilters(array $logs, Request $request): array
    {
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');
        $adminId = $request->input('admin_id');
        $module = $request->input('module');
        $action = $request->input('action');

        return array_filter($logs, function ($log) use ($fromDate, $toDate, $adminId, $module, $action) {
            // Date range filter
            if ($fromDate || $toDate) {
                $logTimestamp = $log['timestamp'] ?? '';
                if ($logTimestamp) {
                    try {
                        $logDate = \Carbon\Carbon::parse($logTimestamp);

                        if ($fromDate) {
                            $from = \Carbon\Carbon::parse($fromDate)->startOfDay();
                            if ($logDate->lt($from)) {
                                return false;
                            }
                        }

                        if ($toDate) {
                            $to = \Carbon\Carbon::parse($toDate)->endOfDay();
                            if ($logDate->gt($to)) {
                                return false;
                            }
                        }
                    } catch (\Exception $e) {
                        // If date parsing fails, exclude the log
                        return false;
                    }
                } else {
                    // If no timestamp, exclude when date filter is active
                    if ($fromDate || $toDate) {
                        return false;
                    }
                }
            }

            // Admin filter
            if ($adminId && isset($log['performed_by_id'])) {
                if ((int) $log['performed_by_id'] !== (int) $adminId) {
                    return false;
                }
            }

            // Module filter
            if ($module && isset($log['action'])) {
                $logAction = strtolower($log['action']);
                $moduleLower = strtolower($module);

                $actionParts = explode('_', $logAction);
                $actionPrefix = $actionParts[0] ?? '';

                if ($actionPrefix !== $moduleLower && strpos($logAction, $moduleLower) === false) {
                    return false;
                }
            }

            // Action filter
            if ($action && isset($log['action'])) {
                if (strtolower($log['action']) !== strtolower($action)) {
                    return false;
                }
            }

            return true;
        });
    }

    private function applySearch(array $logs, string $searchValue): array
    {
        $searchValue = strtolower($searchValue);

        return array_filter($logs, function ($log) use ($searchValue) {
            $searchableFields = [
                $log['action'] ?? '',
                $log['performed_by_email'] ?? '',
                $log['performed_by_ip'] ?? '',
                json_encode($log['metadata'] ?? []),
            ];

            foreach ($searchableFields as $field) {
                if (stripos(strtolower($field), $searchValue) !== false) {
                    return true;
                }
            }

            return false;
        });
    }

    private function formatTimestamp(string $timestamp): string
    {
        try {
            $date = \Carbon\Carbon::parse($timestamp);
            return $date->format('d-m-Y H:i');
        } catch (\Exception $e) {
            return $timestamp;
        }
    }
}
