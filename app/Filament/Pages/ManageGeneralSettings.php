<?php

namespace App\Filament\Pages;

use App\Settings\GeneralSettings;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Pages\SettingsPage;

class ManageGeneralSettings extends SettingsPage
{
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static string $settings = GeneralSettings::class;

    protected static string | \UnitEnum | null $navigationGroup = 'Settings';
    
    protected static ?int $navigationSort = 1;

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Forms\Components\TextInput::make('site_name')
                    ->label('Site Name')
                    ->required(),
                Forms\Components\Toggle::make('site_active')
                    ->label('Site Active'),
            ]);
    }
}
