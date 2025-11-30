<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Dashboard\Auth\AdminAuthController;
use App\Http\Controllers\Dashboard\SiteText\SiteTextController;
use App\Http\Controllers\Dashboard\Setting\SettingController;
use App\Http\Controllers\Dashboard\Profile\ProfileController;
use App\Http\Controllers\Dashboard\Admins\AdminController;
use App\Http\Controllers\Dashboard\Cities\CityController;
use App\Http\Controllers\Dashboard\Services\ServiceController;
use App\Http\Controllers\Dashboard\FreeDesigns\FreeDesignController;
Route::prefix('dashboard')->name('dashboard.')->group(function () {
    // Phone Codes Route (public - no authentication required)
    Route::get('/phone-codes', function () {
        // Execute the PHP file and capture its output
        ob_start();
        include public_path('phoneCode.php');
        $output = ob_get_clean();

        // Return the JSON response
        return response($output)
            ->header('Content-Type', 'application/json')
            ->header('Access-Control-Allow-Origin', '*')
            ->header('Access-Control-Allow-Methods', 'GET, POST, OPTIONS')
            ->header('Access-Control-Allow-Headers', 'Content-Type');
    })->name('phone-codes');

    // Guest routes (for non-authenticated users)
    Route::middleware('guest:admin')->group(function () {
        Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [AdminAuthController::class, 'login']);
    });

    // Authenticated routes (for logged in users)
    Route::middleware('auth.admin')->group(function () {
        // Dashboard route
        Route::get('/', function () {
            return view('dashboard.pages.dashboard');
        })->name('dashboard');

        // Profile routes
        Route::prefix('profile')->name('profile.')->group(function () {
            Route::get('/', [ProfileController::class, 'index'])->name('index');
            Route::put('/', [ProfileController::class, 'update'])->name('update');
        });

        // Logout route
        Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');

        // Site Text routes
        Route::prefix('site-text')->name('site-text.')->group(function () {
            Route::get('{type}', [SiteTextController::class, 'show'])
                ->where('type', 'home_banner|how_work|features|increase_profits')
                ->name('show');
            Route::post('{type}/update', [SiteTextController::class, 'update'])
                ->where('type', 'home_banner|how_work|features|increase_profits')
                ->name('update');
        });

        // Settings Routes
        Route::prefix('settings')->name('settings.')->group(function () {
            Route::get('/', [SettingController::class, 'index'])->name('index');
            Route::put('/', [SettingController::class, 'update'])->name('update');
            Route::get('/social-media', [SettingController::class, 'socialMediaIndex'])->name('social-media.index');
            Route::put('/social-media', [SettingController::class, 'updateSocialMedia'])->name('social-media.update');
        });

        // Cities Routes (إدارة المدن)
        Route::get('cities', [CityController::class, 'index'])->name('cities.index');

        // Services Routes (إدارة الخدمات)
        Route::get('services', [ServiceController::class, 'index'])->name('services.index');

        // Free Designs Routes (إدارة الخدمات المجانية)
        Route::get('free-designs', [FreeDesignController::class, 'index'])->name('free-designs.index');

        // Admins Routes
        Route::delete('/admins/bulk-destroy', [AdminController::class, 'bulkDestroy'])->name('admins.bulk-destroy');
        Route::resource('admins', AdminController::class);
        Route::patch('/admins/{admin}/toggle-status', [AdminController::class, 'toggleStatus'])->name('admins.toggle-status');

    });
});
