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
        'total_projects',
        'total_technologies',
    ];

    protected $casts = [
        'gpa' => 'decimal:2',
        'total_projects' => 'integer',
        'total_technologies' => 'integer',
    ];
}
