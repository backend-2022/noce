<?php

use App\Http\Controllers\Website\HomeController;
use App\Http\Controllers\Website\FreeDesignController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::post('/free-design', [FreeDesignController::class, 'store'])->name('free-design.store');
