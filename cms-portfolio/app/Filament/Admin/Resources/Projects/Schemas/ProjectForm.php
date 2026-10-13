<?php

namespace App\Filament\Admin\Resources\Projects\Schemas;

use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class ProjectForm
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

                CheckboxList::make('category')
                    ->label('Kategori')
                    ->options(array_map(
                        fn (array $labels) => $labels['id'],
                        config('categories')
                    ))
                    ->required()
                    ->columns(2),

                TagsInput::make('tech_stack')
                    ->label('Tech Stack')
                    ->placeholder('Tambahkan teknologi satu per satu'),

                FileUpload::make('image')
                    ->label('Thumbnail')
                    ->image()
                    ->disk('public')
                    ->directory('project-thumbnails'),
            ]);
    }
}
