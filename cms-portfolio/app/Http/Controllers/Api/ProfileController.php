<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProfileResource;
use App\Models\Profile;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function show(Request $request): JsonResponse
    {
        $locale = $request->getPreferredLanguage(config('app.available_locales'));

        app()->setLocale($locale ?? config('app.locale'));

        $profile = Profile::query()->firstOrFail();

        return ProfileResource::make($profile)
            ->response()
            ->setStatusCode(200);
    }
}
