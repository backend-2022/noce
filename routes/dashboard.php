<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Dashboard\Auth\AdminAuthController;
use App\Http\Controllers\Dashboard\Setting\SettingController;
use App\Http\Controllers\Dashboard\Profile\ProfileController;
use App\Http\Controllers\Dashboard\Admins\AdminController;
use App\Http\Controllers\Dashboard\Cities\CityController;
use App\Http\Controllers\Dashboard\Services\ServiceController;
use App\Http\Controllers\Dashboard\FreeDesigns\FreeDesignController;
use App\Http\Controllers\Dashboard\Backups\BackupController;
use App\Http\Controllers\Dashboard\ActivityLogs\ActivityLogController;
use App\Http\Controllers\Dashboard\AdminPermissions\AdminPermissionController;
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
            return redirect()->route('dashboard.free-designs.index');
        })->name('dashboard');

        // Profile routes
        Route::prefix('profile')->name('profile.')->group(function () {
            Route::get('/', [ProfileController::class, 'index'])->name('index');
            Route::put('/', [ProfileController::class, 'update'])->name('update');
        });

        // Logout route
        Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');


        // Cities Routes
        Route::middleware('permission:cities.view')->group(function () {
            Route::get('cities', [CityController::class, 'index'])->name('cities.index');
        });
        Route::middleware('permission:cities.create')->group(function () {
            Route::get('cities/create', [CityController::class, 'create'])->name('cities.create');
            Route::post('cities', [CityController::class, 'store'])->name('cities.store');
        });
        Route::middleware('permission:cities.update')->group(function () {
            Route::get('cities/{city}/edit', [CityController::class, 'edit'])->name('cities.edit');
            Route::put('cities/{city}', [CityController::class, 'update'])->name('cities.update');
            Route::patch('/cities/{city}/toggle-status', [CityController::class, 'toggleStatus'])->name('cities.toggle-status');
        });
        Route::middleware('permission:cities.delete')->group(function () {
            Route::delete('cities/{city}', [CityController::class, 'destroy'])->name('cities.destroy');
        });

        // Services Routes (إدارة الخدمات)
        Route::middleware('permission:services.view')->group(function () {
            Route::get('services', [ServiceController::class, 'index'])->name('services.index');
        });
        Route::middleware('permission:services.create')->group(function () {
            Route::get('services/create', [ServiceController::class, 'create'])->name('services.create');
            Route::post('services', [ServiceController::class, 'store'])->name('services.store');
        });
        Route::middleware('permission:services.update')->group(function () {
            Route::get('services/{service}/edit', [ServiceController::class, 'edit'])->name('services.edit');
            Route::put('services/{service}', [ServiceController::class, 'update'])->name('services.update');
            Route::patch('/services/{service}/toggle-status', [ServiceController::class, 'toggleStatus'])->name('services.toggle-status');
        });
        Route::middleware('permission:services.delete')->group(function () {
            Route::delete('services/{service}', [ServiceController::class, 'destroy'])->name('services.destroy');
        });

        // Free Designs Routes (إدارة الخدمات المجانية)
        Route::middleware('permission:free-services.view')->group(function () {
            Route::get('free-designs', [FreeDesignController::class, 'index'])->name('free-designs.index');
        });
        Route::middleware('permission:free-services.delete')->group(function () {
            Route::delete('free-designs/{freeDesign}', [FreeDesignController::class, 'destroy'])->name('free-designs.destroy');
        });

        // Admins Routes
        Route::middleware('permission:admins.view')->group(function () {
            Route::get('admins', [AdminController::class, 'index'])->name('admins.index');
        });
        Route::middleware('permission:admins.create')->group(function () {
            Route::get('admins/create', [AdminController::class, 'create'])->name('admins.create');
            Route::post('admins', [AdminController::class, 'store'])->name('admins.store');
        });
        Route::middleware('permission:admins.update')->group(function () {
            Route::get('admins/{admin}/edit', [AdminController::class, 'edit'])->name('admins.edit');
            Route::put('admins/{admin}', [AdminController::class, 'update'])->name('admins.update');
            Route::patch('/admins/{admin}/toggle-status', [AdminController::class, 'toggleStatus'])->name('admins.toggle-status');
        });
        Route::middleware('permission:admins.delete')->group(function () {
            Route::delete('/admins/bulk-destroy', [AdminController::class, 'bulkDestroy'])->name('admins.bulk-destroy');
            Route::delete('admins/{admin}', [AdminController::class, 'destroy'])->name('admins.destroy');
        });

        // Backups Routes
        Route::middleware('permission:backup.view')->group(function () {
            Route::get('backups', [BackupController::class, 'index'])->name('backups.index');
            Route::get('backups/{backup}/download', [BackupController::class, 'download'])->name('backups.download');
        });
        Route::middleware('permission:backup.delete')->group(function () {
            Route::delete('backups/{backup}', [BackupController::class, 'destroy'])->name('backups.destroy');
        });

        // Activity Logs Routes
        Route::middleware('permission:activity-logs.view')->group(function () {
            Route::get('activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');
        });

        // Admin Permissions Routes
        Route::prefix('admin-permissions')->name('admin-permissions.')->middleware('permission:permissions.view')->group(function () {
            Route::get('/', [AdminPermissionController::class, 'index'])->name('index');
            Route::get('/{admin}/edit', [AdminPermissionController::class, 'edit'])->name('edit');
            Route::put('/{admin}', [AdminPermissionController::class, 'update'])->name('update');
        });

        // Settings Routes
        Route::prefix('settings')->name('settings.')->middleware('permission:settings.view')->group(function () {
            Route::get('/', [SettingController::class, 'index'])->name('index');
            Route::get('/social-media', [SettingController::class, 'socialMediaIndex'])->name('social-media.index');
            Route::get('/seo', [SettingController::class, 'seoIndex'])->name('seo.index');
        });
        Route::prefix('settings')->name('settings.')->middleware('permission:settings.update')->group(function () {
            Route::put('/', [SettingController::class, 'update'])->name('update');
            Route::put('/social-media', [SettingController::class, 'updateSocialMedia'])->name('social-media.update');
            Route::put('/seo', [SettingController::class, 'updateSeo'])->name('seo.update');
        });

    });
});
