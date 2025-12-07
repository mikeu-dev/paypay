<?php

namespace App\Filament\Pages;

use App\Settings\AnalyticsSettings;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Pages\SettingsPage;

class ManageAnalyticsSettings extends SettingsPage
{
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-chart-bar';

    protected static string $settings = AnalyticsSettings::class;

    protected static string | \UnitEnum | null $navigationGroup = 'Settings';

    protected static ?int $navigationSort = 3;

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Forms\Components\TextInput::make('ga_id')
                    ->label('Google Analytics ID')
                    ->placeholder('UA-XXXXX-Y'),
                Forms\Components\TextInput::make('pixel_id')
                    ->label('Facebook Pixel ID')
                    ->placeholder('XXXXXXXXXXXXXXX'),
            ]);
    }
}
