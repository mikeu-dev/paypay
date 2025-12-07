<?php

namespace App\Filament\Pages;

use App\Settings\SeoSettings;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Pages\SettingsPage;

class ManageSeoSettings extends SettingsPage
{
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-globe-alt';

    protected static string $settings = SeoSettings::class;

    protected static string | \UnitEnum | null $navigationGroup = 'Settings';

    protected static ?int $navigationSort = 2;

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Forms\Components\TextInput::make('meta_title')
                    ->label('Meta Title')
                    ->required(),
                Forms\Components\Textarea::make('meta_description')
                    ->label('Meta Description'),
                Forms\Components\TagsInput::make('meta_keywords')
                    ->label('Meta Keywords'),
            ]);
    }
}
