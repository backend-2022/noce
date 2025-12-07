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

        // Settings Routes
        Route::prefix('settings')->name('settings.')->group(function () {
            Route::get('/', [SettingController::class, 'index'])->name('index');
            Route::put('/', [SettingController::class, 'update'])->name('update');
            Route::get('/social-media', [SettingController::class, 'socialMediaIndex'])->name('social-media.index');
            Route::put('/social-media', [SettingController::class, 'updateSocialMedia'])->name('social-media.update');
        });

        Route::resource('cities', CityController::class);
        Route::patch('/cities/{city}/toggle-status', [CityController::class, 'toggleStatus'])->name('cities.toggle-status');

        // Services Routes (إدارة الخدمات)
        Route::resource('services', ServiceController::class);
        Route::patch('/services/{service}/toggle-status', [ServiceController::class, 'toggleStatus'])->name('services.toggle-status');

        // Free Designs Routes (إدارة الخدمات المجانية)
        Route::get('free-designs', [FreeDesignController::class, 'index'])->name('free-designs.index');
        Route::delete('free-designs/{freeDesign}', [FreeDesignController::class, 'destroy'])->name('free-designs.destroy');

        // Admins Routes
        Route::delete('/admins/bulk-destroy', [AdminController::class, 'bulkDestroy'])->name('admins.bulk-destroy');
        Route::resource('admins', AdminController::class);
        Route::patch('/admins/{admin}/toggle-status', [AdminController::class, 'toggleStatus'])->name('admins.toggle-status');

        // Backups Routes
        Route::get('backups', [BackupController::class, 'index'])->name('backups.index');
        Route::get('backups/{backup}/download', [BackupController::class, 'download'])->name('backups.download');
        Route::delete('backups/{backup}', [BackupController::class, 'destroy'])->name('backups.destroy');

    });
});
