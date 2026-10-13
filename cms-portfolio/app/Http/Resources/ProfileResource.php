<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProfileResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'title' => $this->title,
            'about' => $this->about,
            'photo' => $this->photo ? asset('storage/'.$this->photo) : null,
            'gpa' => $this->gpa,
            'resume' => $this->resume ? asset('storage/'.$this->resume) : null,
            'social_links' => [
                'instagram' => $this->instagram,
                'whatsapp' => $this->whatsapp,
                'github' => $this->github,
                'linkedin' => $this->linkedin,
            ],
        ];
    }
}
