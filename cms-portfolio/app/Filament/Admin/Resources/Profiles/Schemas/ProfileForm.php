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
                    ->imageResizeMode('cover')
                    ->imageResizeTargetWidth('800')
                    ->imageResizeTargetHeight('800')
                    ->disk('public')
                    ->directory('profile-photos'),

                TextInput::make('gpa')
                    ->label('IPK')
                    ->numeric()
                    ->minValue(0)
                    ->maxValue(4)
                    ->step(0.01),

                FileUpload::make('resume')
                    ->label('Resume / CV')
                    ->disk('public')
                    ->directory('profile-resumes')
                    ->acceptedFileTypes(['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document']),

                TextInput::make('instagram')
                    ->label('Instagram')
                    ->url()
                    ->placeholder('https://instagram.com/username')
                    ->maxLength(255),

                TextInput::make('whatsapp')
                    ->label('WhatsApp')
                    ->url()
                    ->placeholder('https://wa.me/6281234567890')
                    ->maxLength(255),

                TextInput::make('github')
                    ->label('GitHub')
                    ->url()
                    ->placeholder('https://github.com/username')
                    ->maxLength(255),

                TextInput::make('linkedin')
                    ->label('LinkedIn')
                    ->url()
                    ->placeholder('https://linkedin.com/in/username')
                    ->maxLength(255),
            ]);
    }
}
