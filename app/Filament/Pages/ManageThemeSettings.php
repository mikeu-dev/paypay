<?php

namespace App\Filament\Pages;

use App\Settings\ThemeSettings;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Pages\SettingsPage;

class ManageThemeSettings extends SettingsPage
{
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-paint-brush';

    protected static string $settings = ThemeSettings::class;

    protected static string | \UnitEnum | null $navigationGroup = 'Settings';

    protected static ?int $navigationSort = 6;

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Forms\Components\ColorPicker::make('primary_color')
                    ->label('Primary Color')
                    ->required(),
                Forms\Components\TextInput::make('logo_url')
                    ->label('Logo URL')
                    ->url(),
                Forms\Components\TextInput::make('favicon_url')
                    ->label('Favicon URL')
                    ->url(),
                Forms\Components\Select::make('navigation_layout')
                    ->label('Navigation Layout')
                    ->options([
                        'sidebar' => 'Sidebar',
                        'topbar' => 'Topbar',
                    ])
                    ->required(),
                Forms\Components\Select::make('font_family')
                    ->label('Font Family')
                    ->options([
                        'Inter' => 'Inter',
                        'Roboto' => 'Roboto',
                        'Open Sans' => 'Open Sans',
                        'Poppins' => 'Poppins',
                        'Lato' => 'Lato',
                    ])
                    ->required(),
            ]);
    }
}
