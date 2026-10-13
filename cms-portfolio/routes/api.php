<?php

use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\SkillController;
use Illuminate\Support\Facades\Route;

Route::get('/profile', [ProfileController::class, 'show']);
Route::get('/skills', [SkillController::class, 'index']);
