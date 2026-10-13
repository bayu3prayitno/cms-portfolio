<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Experience extends Model
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
        'year',
        'photo',
        'certificate_file',
        'certificate_link',
        'publication_link',
    ];

    public function getCategoryLabel(?string $locale = null): string
    {
        $locale ??= app()->getLocale();

        return config("experience_categories.{$this->category}.{$locale}", $this->category);
    }
}
