<?php

namespace App\Filament\Admin\Resources\Profiles;

use App\Filament\Admin\Resources\Profiles\Pages\CreateProfile;
use App\Filament\Admin\Resources\Profiles\Pages\EditProfile;
use App\Filament\Admin\Resources\Profiles\Pages\ListProfiles;
use App\Filament\Admin\Resources\Profiles\Schemas\ProfileForm;
use App\Filament\Admin\Resources\Profiles\Tables\ProfilesTable;
use App\Models\Profile;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use LaraZeus\SpatieTranslatable\Resources\Concerns\Translatable;

class ProfileResource extends Resource
{
    use Translatable;

    protected static ?string $model = Profile::class;

    /**
     * Only one profile record may ever exist.
     */
    public static function canCreate(): bool
    {
        return ! Profile::query()->exists();
    }

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $navigationLabel = 'Profil';

    protected static ?string $modelLabel = 'Profil';

    public static function form(Schema $schema): Schema
    {
        return ProfileForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ProfilesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListProfiles::route('/'),
            'create' => CreateProfile::route('/create'),
            'edit' => EditProfile::route('/{record}/edit'),
        ];
    }
}
