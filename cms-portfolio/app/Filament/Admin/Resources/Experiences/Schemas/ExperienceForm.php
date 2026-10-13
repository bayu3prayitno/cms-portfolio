<?php

namespace App\Filament\Admin\Resources\Experiences\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class ExperienceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->label('Judul')
                    ->required()
                    ->maxLength(255),

                Textarea::make('description')
                    ->label('Deskripsi')
                    ->rows(6)
                    ->columnSpanFull(),

                Select::make('category')
                    ->label('Kategori')
                    ->options(array_map(
                        fn (array $labels) => $labels['id'],
                        config('experience_categories')
                    ))
                    ->required()
                    ->live(),

                TextInput::make('year')
                    ->label('Tahun')
                    ->maxLength(255),

                FileUpload::make('photo')
                    ->label('Foto Kegiatan')
                    ->image()
                    ->imageResizeMode('cover')
                    ->imageResizeTargetWidth('1280')
                    ->imageResizeTargetHeight('720')
                    ->disk('public')
                    ->directory('experience-photos')
                    ->visible(fn (Get $get): bool => $get('category') === 'activity'),

                FileUpload::make('certificate_file')
                    ->label('File Sertifikat (PDF)')
                    ->acceptedFileTypes(['application/pdf'])
                    ->disk('public')
                    ->directory('experience-certificates')
                    ->visible(fn (Get $get): bool => $get('category') === 'certification'),

                TextInput::make('certificate_link')
                    ->label('Link Sertifikat')
                    ->url()
                    ->maxLength(255)
                    ->visible(fn (Get $get): bool => $get('category') === 'certification'),

                TextInput::make('publication_link')
                    ->label('Link Publikasi')
                    ->url()
                    ->maxLength(255)
                    ->visible(fn (Get $get): bool => $get('category') === 'publication'),
            ]);
    }
}
