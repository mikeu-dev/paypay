<?php

namespace App\Filament\Pages;

use App\Settings\SocialSettings;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Pages\SettingsPage;

class ManageSocialSettings extends SettingsPage
{
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-share';

    protected static string $settings = SocialSettings::class;

    protected static string | \UnitEnum | null $navigationGroup = 'Settings';

    protected static ?int $navigationSort = 5;

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Forms\Components\TextInput::make('facebook_url')
                    ->label('Facebook URL')
                    ->url(),
                Forms\Components\TextInput::make('twitter_url')
                    ->label('Twitter URL')
                    ->url(),
                Forms\Components\TextInput::make('instagram_url')
                    ->label('Instagram URL')
                    ->url(),
                Forms\Components\TextInput::make('linkedin_url')
                    ->label('LinkedIn URL')
                    ->url(),
            ]);
    }
}
