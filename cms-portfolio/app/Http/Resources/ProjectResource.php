<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'category' => $this->getCategoryLabels(),
            'tech_stack' => $this->tech_stack,
            'image' => $this->image ? asset('storage/'.$this->image) : null,
        ];
    }
}
