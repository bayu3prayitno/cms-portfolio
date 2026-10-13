<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ExperienceResource;
use App\Models\Experience;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ExperienceController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        return ExperienceResource::collection(
            Experience::query()
                ->when($request->query('category'), fn ($query, $category) => $query->where('category', $category))
                ->get()
        );
    }
}
