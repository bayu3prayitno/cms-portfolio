<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Profile extends Model
{
    use HasTranslations;

    public array $translatable = [
        'title',
        'about',
    ];

    protected $fillable = [
        'name',
        'title',
        'about',
        'photo',
        'gpa',
        'resume',
        'instagram',
        'whatsapp',
        'github',
        'linkedin',
    ];

    protected $casts = [
        'gpa' => 'decimal:2',
    ];
}
