<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProfileResource;
use App\Models\Profile;
use Illuminate\Http\JsonResponse;

class ProfileController extends Controller
{
    public function show(): JsonResponse
    {
        $profile = Profile::query()->firstOrFail();

        return ProfileResource::make($profile)
            ->response()
            ->setStatusCode(200);
    }
}
