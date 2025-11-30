<?php

use App\Http\Controllers\Website\SiteTextController;
use Illuminate\Support\Facades\Route;

Route::get('/', [SiteTextController::class, 'index'])->name('home');
