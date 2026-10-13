<?php

namespace App\Filament\Admin\Resources\Profiles\Pages;

use App\Filament\Admin\Resources\Profiles\ProfileResource;
use App\Models\Profile;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use LaraZeus\SpatieTranslatable\Actions\LocaleSwitcher;
use LaraZeus\SpatieTranslatable\Resources\Pages\ListRecords\Concerns\Translatable;

class ListProfiles extends ListRecords
{
    use Translatable;

    protected static string $resource = ProfileResource::class;

    public function mount(): void
    {
        parent::mount();

        if ($profile = Profile::query()->first()) {
            $this->redirect(ProfileResource::getUrl('edit', ['record' => $profile]));
        }
    }

    protected function getHeaderActions(): array
    {
        return [
            LocaleSwitcher::make(),
            CreateAction::make(),
        ];
    }
}
