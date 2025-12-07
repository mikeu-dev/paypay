<?php

namespace App\Filament\Pages;

use App\Settings\EmailSettings;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Pages\SettingsPage;

class ManageEmailSettings extends SettingsPage
{
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-envelope';

    protected static string $settings = EmailSettings::class;

    protected static string | \UnitEnum | null $navigationGroup = 'Settings';

    protected static ?int $navigationSort = 4;

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Forms\Components\TextInput::make('support_email')
                    ->label('Support Email')
                    ->email()
                    ->required(),
                Forms\Components\TextInput::make('smtp_host')
                    ->label('SMTP Host'),
            ]);
    }
}
