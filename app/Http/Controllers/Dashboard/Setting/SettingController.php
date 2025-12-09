<?php

namespace App\Http\Controllers\Dashboard\Setting;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Setting\UpdateGeneralSettingRequest;
use App\Http\Requests\Dashboard\Setting\UpdateSocialMediaRequest;
use App\Http\Requests\Dashboard\Setting\UpdateSeoSettingRequest;
use App\Repositories\Interfaces\SettingRepositoryInterface;
use App\Models\Setting;
use App\Traits\FileUploads;
use App\Traits\AdminActivityLogger;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Exception;
use Illuminate\Support\Facades\Http;

class SettingController extends Controller
{
    use FileUploads, AdminActivityLogger;

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
            $settings = $request->except(['_token', '_method', 'logo', 'home_banner']);

            // Handle logo upload
            if ($request->hasFile('logo')) {
                // Delete old logo if exists
                $oldLogo = $this->settingRepository->getByKey('logo');
                if ($oldLogo && $oldLogo->value) {
                    $this->deleteFile($oldLogo->value, null, 'public');
                }

                $uploadedFile = $request->file('logo');
                $path = $this->uploadFile($uploadedFile, null, Setting::$STORAGE_DIR, 'public');
                $this->settingRepository->createOrUpdate('logo', $path);
            }

            // Handle home_banner upload
            if ($request->hasFile('home_banner')) {
                // Delete old home_banner if exists
                $oldHomeBanner = $this->settingRepository->getByKey('home_banner');
                if ($oldHomeBanner && $oldHomeBanner->value) {
                    $this->deleteFile($oldHomeBanner->value, null, 'public');
                }

                $uploadedFile = $request->file('home_banner');
                $path = $this->uploadFile($uploadedFile, null, Setting::$STORAGE_DIR, 'public');
                $this->settingRepository->createOrUpdate('home_banner', $path);
            }

            // Handle keep_backups toggle
            if (!isset($settings['keep_backups'])) {
                $settings['keep_backups'] = '0';
            }

            foreach ($settings as $key => $value) {
                // Convert empty strings to null to clear old data
                $value = $value === '' ? null : $value;

                // Convert Google Maps share links to embed format
                if ($key === 'map_link' && $value) {
                    // Extract URL from iframe if user pasted full iframe code
                    if (preg_match('/<iframe[^>]+src=["\']([^"\']+)["\']/', $value, $matches)) {
                        $value = $matches[1]; // Extract src attribute from iframe
                    } elseif (preg_match('/src=["\']([^"\']+)["\']/', $value, $matches)) {
                        $value = $matches[1]; // Extract src if no iframe tag
                    }

                    // Clean up the value (remove any HTML tags that might remain)
                    $value = strip_tags($value);
                    $value = trim($value);

                    // If it's already an embed link, don't convert it
                    if (str_contains($value, 'google.com/maps/embed')) {
                        // Keep the embed link as is - no conversion needed
                        $this->settingRepository->createOrUpdate($key, $value);
                        continue;
                    }

                    // Try to resolve maps.app.goo.gl share links to get the actual location
                    // Example: https://maps.app.goo.gl/1QraV4v8bLZApmj78
                    if (str_contains($value, 'maps.app.goo.gl')) {
                        try {
                            // Follow redirects to get the actual Google Maps URL with location data
                            $response = Http::withOptions([
                                'allow_redirects' => [
                                    'max' => 5,
                                    'strict' => true,
                                    'referer' => true,
                                    'protocols' => ['http', 'https'],
                                ],
                                'timeout' => 10,
                            ])->get($value);

                            // Get the final URL after redirects (contains actual location)
                            $finalUrl = $response->effectiveUri();

                            if ($finalUrl && $finalUrl != $value && str_contains((string)$finalUrl, 'google.com/maps')) {
                                // Extract location information from the resolved URL
                                $resolvedUrl = (string) $finalUrl;

                                // Try to extract place ID
                                if (preg_match('/\/place\/([^\/\?]+)/', $resolvedUrl, $matches)) {
                                    $placeId = $matches[1];
                                    // Use place ID for embed (most reliable)
                                    $value = "https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d0!2d0!3d0!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2z" . urlencode($placeId) . "!5e0!3m2!1sen!2sus!4v" . time() . "!5m2!1sen!2sus";
                                }
                                // Try to extract coordinates
                                elseif (preg_match('/@(-?\d+\.?\d*),(-?\d+\.?\d*)/', $resolvedUrl, $matches)) {
                                    $lat = $matches[1];
                                    $lng = $matches[2];
                                    $value = "https://www.google.com/maps?q=" . $lat . "," . $lng . "&output=embed";
                                }
                                // Use the resolved URL directly
                                else {
                                    $value = $resolvedUrl;
                                }
                            }
                        } catch (Exception $e) {
                            // If resolution fails, keep the share link for JavaScript to handle
                        }
                    }

                    // Convert to embed format (works with both resolved URLs and share links)
                    $value = convertGoogleMapsLinkToEmbed($value);
                }

                $this->settingRepository->createOrUpdate($key, $value);
            }

            // Get updated settings for response
            $updatedSettings = $this->settingRepository->getAll();
            $settingsArray = [];
            foreach ($updatedSettings as $setting) {
                $settingsArray[$setting->key] = $setting->value;
            }

            // Add logo URL to response if exists
            if (isset($settingsArray['logo']) && $settingsArray['logo']) {
                $settingsArray['logo_url'] = $this->getFileUrl($settingsArray['logo'], null, 'public');
            }

            // Add home_banner URL to response if exists
            if (isset($settingsArray['home_banner']) && $settingsArray['home_banner']) {
                $settingsArray['home_banner_url'] = $this->getFileUrl($settingsArray['home_banner'], null, 'public');
            }

            $this->logActivity('settings_updated', [
                'updated_keys' => array_keys($settings),
            ]);

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

            $this->logActivity('social_media_settings_updated', [
                'updated_keys' => array_keys($settings),
            ]);

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

    public function seoIndex(): View|RedirectResponse
    {

        $defaultSettings = $this->settingRepository->getAll();

        $settings = [];
        foreach ($defaultSettings as $setting) {
            $settings[$setting->key] = $setting->value;
        }

        return view('dashboard.pages.settings.seo', compact('settings'));
    }

    public function updateSeo(UpdateSeoSettingRequest $request): JsonResponse|RedirectResponse
    {

        try {
            $settings = $request->except(['_token', '_method']);

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

            $this->logActivity('seo_settings_updated', [
                'updated_keys' => array_keys($settings),
            ]);

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'تم تحديث بيانات SEO بنجاح',
                    'data' => [
                        'settings' => $settingsArray
                    ]
                ]);
            }

            return redirect()->route('dashboard.settings.seo.index')
                ->with('success', 'تم تحديث بيانات SEO بنجاح');
        } catch (Exception $e) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'حدث خطأ أثناء تحديث بيانات SEO',
                ], 500);
            }

            return redirect()->route('dashboard.settings.seo.index')
                ->with('error', 'حدث خطأ أثناء تحديث بيانات SEO');
        }
    }
}
