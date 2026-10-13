<?php

namespace App\Filament\Admin\Resources\Profiles\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class ProfileForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nama')
                    ->required()
                    ->maxLength(255),

                TextInput::make('title')
                    ->label('Gelar')
                    ->required()
                    ->maxLength(255),

                Textarea::make('about')
                    ->label('Tentang')
                    ->required()
                    ->rows(5)
                    ->columnSpanFull(),

                FileUpload::make('photo')
                    ->label('Foto Profil')
                    ->image()
                    ->disk('public')
                    ->directory('profile-photos'),

                TextInput::make('gpa')
                    ->label('IPK')
                    ->numeric()
                    ->minValue(0)
                    ->maxValue(4)
                    ->step(0.01),

                TextInput::make('total_projects')
                    ->label('Jumlah Proyek')
                    ->numeric()
                    ->minValue(0)
                    ->default(0)
                    ->required(),

                TextInput::make('total_technologies')
                    ->label('Jumlah Teknologi')
                    ->numeric()
                    ->minValue(0)
                    ->default(0)
                    ->required(),
            ]);
    }
}
