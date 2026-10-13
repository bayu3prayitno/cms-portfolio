<?php

namespace App\Filament\Admin\Resources\Skills\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class SkillForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nama Teknologi')
                    ->required()
                    ->maxLength(255),

                FileUpload::make('icon')
                    ->label('Ikon/Logo')
                    ->image()
                    ->disk('public')
                    ->directory('skill-icons'),
            ]);
    }
}
