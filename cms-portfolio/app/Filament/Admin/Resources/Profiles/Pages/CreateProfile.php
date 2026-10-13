<?php

namespace App\Filament\Admin\Resources\Profiles\Pages;

use App\Filament\Admin\Resources\Profiles\ProfileResource;
use App\Models\Profile;
use Filament\Resources\Pages\CreateRecord;
use LaraZeus\SpatieTranslatable\Actions\LocaleSwitcher;
use LaraZeus\SpatieTranslatable\Resources\Pages\CreateRecord\Concerns\Translatable;

class CreateProfile extends CreateRecord
{
    use Translatable;

    protected static string $resource = ProfileResource::class;

    public function mount(): void
    {
        if ($profile = Profile::query()->first()) {
            $this->redirect(ProfileResource::getUrl('edit', ['record' => $profile]));

            return;
        }

        parent::mount();
    }

    protected function getHeaderActions(): array
    {
        return [
            LocaleSwitcher::make(),
        ];
    }
}
