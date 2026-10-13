<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExperienceResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'category' => $this->getCategoryLabel(),
            'year' => $this->year,
            'photo' => $this->photo ? asset('storage/'.$this->photo) : null,
            'certificate_file' => $this->certificate_file ? asset('storage/'.$this->certificate_file) : null,
            'certificate_link' => $this->certificate_link,
            'publication_link' => $this->publication_link,
        ];
    }
}
