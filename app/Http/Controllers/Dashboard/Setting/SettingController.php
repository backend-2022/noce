<?php

namespace App\Http\Controllers\Dashboard\Setting;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Setting\UpdateGeneralSettingRequest;
use App\Http\Requests\Dashboard\Setting\UpdateSocialMediaRequest;
use App\Repositories\Interfaces\SettingRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Exception;

class SettingController extends Controller
{
    protected SettingRepositoryInterface $settingRepository;

    public function __construct(SettingRepositoryInterface $settingRepository)
    {
        $this->settingRepository = $settingRepository;
    }

    public function index(): View|RedirectResponse
    {
        $defaultSettings = $this->settingRepository->getAll();

        $settings = [];
        foreach ($defaultSettings as $setting) {
            $settings[$setting->key] = $setting->value;
        }

        return view('dashboard.pages.settings.index', compact('settings'));
    }

    public function socialMediaIndex(): View|RedirectResponse
    {
        $defaultSettings = $this->settingRepository->getAll();

        $settings = [];
        foreach ($defaultSettings as $setting) {
            $settings[$setting->key] = $setting->value;
        }

        return view('dashboard.pages.settings.social-media', compact('settings'));
    }

    public function update(UpdateGeneralSettingRequest $request): JsonResponse|RedirectResponse
    {
        try {
            $settings = $request->except(['_token', '_method']);

            // Handle keep_backups toggle
            if (!isset($settings['keep_backups'])) {
                $settings['keep_backups'] = '0';
            }

            foreach ($settings as $key => $value) {
                // Convert empty strings to null to clear old data
                $value = $value === '' ? null : $value;
                $this->settingRepository->createOrUpdate($key, $value);
            }

            // Get updated settings for response
            $updatedSettings = $this->settingRepository->getAll();
            $settingsArray = [];
            foreach ($updatedSettings as $setting) {
                $settingsArray[$setting->key] = $setting->value;
            }

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'تم تحديث الإعدادات بنجاح',
                    'data' => [
                        'settings' => $settingsArray
                    ]
                ]);
            }

            return redirect()->route('dashboard.settings.index')
                ->with('success', 'تم تحديث الإعدادات بنجاح');
        } catch (Exception $e) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'حدث خطأ أثناء تحديث الإعدادات',
                ], 500);
            }

            return redirect()->route('dashboard.settings.index')
                ->with('error', 'حدث خطأ أثناء تحديث الإعدادات');
        }
    }

    public function updateSocialMedia(UpdateSocialMediaRequest $request): JsonResponse|RedirectResponse
    {
        try {
            $settings = $request->except(['_token', '_method']);

            foreach ($settings as $key => $value) {
                // Convert empty strings to null to clear old data
                $value = $value === '' ? null : $value;

                // Convert whatsapp number to string if it's numeric
                if ($key === 'whatsapp' && $value !== null) {
                    $value = (string) $value;
                }

                $this->settingRepository->createOrUpdate($key, $value);
            }

            // Get updated settings for response
            $updatedSettings = $this->settingRepository->getAll();
            $settingsArray = [];
            foreach ($updatedSettings as $setting) {
                $settingsArray[$setting->key] = $setting->value;
            }

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'تم تحديث روابط التواصل الاجتماعي بنجاح',
                    'data' => [
                        'settings' => $settingsArray
                    ]
                ]);
            }

            return redirect()->route('dashboard.settings.social-media.index')
                ->with('success', 'تم تحديث روابط التواصل الاجتماعي بنجاح');
        } catch (Exception $e) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'حدث خطأ أثناء تحديث روابط التواصل الاجتماعي',
                ], 500);
            }

            return redirect()->route('dashboard.settings.social-media.index')
                ->with('error', 'حدث خطأ أثناء تحديث روابط التواصل الاجتماعي');
        }
    }
}
