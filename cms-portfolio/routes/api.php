<?php

use App\Http\Controllers\Api\ExperienceController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\SkillController;
use App\Http\Middleware\SetApiLocale;
use Illuminate\Support\Facades\Route;

Route::prefix('{locale}')
    ->whereIn('locale', config('app.available_locales'))
    ->middleware(SetApiLocale::class)
    ->group(function () {
        Route::get('/profile', [ProfileController::class, 'show']);
        Route::get('/skills', [SkillController::class, 'index']);
        Route::get('/projects', [ProjectController::class, 'index']);
        Route::get('/experiences', [ExperienceController::class, 'index']);
    });
