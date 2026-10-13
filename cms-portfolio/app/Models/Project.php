<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Project extends Model
{
    use HasTranslations;

    public array $translatable = [
        'title',
        'description',
    ];

    protected $fillable = [
        'title',
        'description',
        'category',
        'tech_stack',
        'image',
    ];

    protected $casts = [
        'category' => 'array',
        'tech_stack' => 'array',
    ];

    public function getCategoryLabels(?string $locale = null): array
    {
        $locale ??= app()->getLocale();

        return array_map(
            fn (string $category) => config("categories.{$category}.{$locale}", $category),
            $this->category ?? []
        );
    }
}
