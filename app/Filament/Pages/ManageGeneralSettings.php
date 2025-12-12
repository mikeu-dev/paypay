<?php

namespace App\Filament\Pages;

use App\Settings\GeneralSettings;
use App\Settings\SeoSettings;
use App\Settings\EmailSettings;
use App\Settings\ThemeSettings;
use App\Settings\SocialSettings;
use App\Settings\AnalyticsSettings;
use Filament\Forms;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Filament\Pages\SettingsPage;

class ManageGeneralSettings extends SettingsPage
{
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static string $settings = GeneralSettings::class;

    protected static string | \UnitEnum | null $navigationGroup = 'Settings';
    
    protected static ?string $navigationLabel = 'Manage Settings';

    protected static ?int $navigationSort = 1;

    public function mount(): void
    {
        parent::mount();
        
        // We need to manually fill the form with data from ALL settings classes
        $this->fillForm();
    }

    protected function fillForm(): void
    {
        $generalSettings = app(GeneralSettings::class);
        $seoSettings = app(SeoSettings::class);
        $emailSettings = app(EmailSettings::class);
        $themeSettings = app(ThemeSettings::class);
        $socialSettings = app(SocialSettings::class);
        $analyticsSettings = app(AnalyticsSettings::class);

        $data = array_merge(
            $generalSettings->toArray(),
            $seoSettings->toArray(),
            $emailSettings->toArray(),
            $themeSettings->toArray(),
            $socialSettings->toArray(),
            $analyticsSettings->toArray()
        );

        $this->form->fill($data);
    }

    public function save(): void
    {
        try {
            $data = $this->form->getState();

            // General
            $generalSettings = app(GeneralSettings::class);
            $generalSettings->site_name = $data['site_name'];
            $generalSettings->site_active = $data['site_active'];
            $generalSettings->save();

            // SEO
            $seoSettings = app(SeoSettings::class);
            $seoSettings->meta_title = $data['meta_title'];
            $seoSettings->meta_description = $data['meta_description'];
            $seoSettings->meta_keywords = $data['meta_keywords'];
            $seoSettings->save();

            // Email
            $emailSettings = app(EmailSettings::class);
            $emailSettings->support_email = $data['support_email'];
            $emailSettings->smtp_host = $data['smtp_host'];
            $emailSettings->save();

            // Theme
            $themeSettings = app(ThemeSettings::class);
            $themeSettings->primary_color = $data['primary_color'];
            $themeSettings->logo_url = $data['logo_url'];
            $themeSettings->favicon_url = $data['favicon_url'];
            $themeSettings->navigation_layout = $data['navigation_layout'];
            $themeSettings->font_family = $data['font_family'];
            $themeSettings->save();

            // Social
            $socialSettings = app(SocialSettings::class);
            $socialSettings->facebook_url = $data['facebook_url'];
            $socialSettings->twitter_url = $data['twitter_url'];
            $socialSettings->instagram_url = $data['instagram_url'];
            $socialSettings->linkedin_url = $data['linkedin_url'];
            $socialSettings->save();

            // Analytics
            $analyticsSettings = app(AnalyticsSettings::class);
            $analyticsSettings->ga_id = $data['ga_id'];
            $analyticsSettings->pixel_id = $data['pixel_id'];
            $analyticsSettings->save();

            $this->callHook('afterSave');

            $this->sendSampleNotification();
        } catch (\Throwable $exception) {
            throw $exception;
        }
    }
    
    protected function sendSampleNotification(): void
    {
        \Filament\Notifications\Notification::make()
            ->title('Settings saved')
            ->success()
            ->send();
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Tabs::make('Settings')
                    ->tabs([
                        Tab::make('General')
                            ->schema([
                                Forms\Components\TextInput::make('site_name')
                                    ->label('Site Name')
                                    ->required(),
                                Forms\Components\Toggle::make('site_active')
                                    ->label('Site Active'),
                            ]),
                        Tab::make('SEO')
                            ->schema([
                                Forms\Components\TextInput::make('meta_title')
                                    ->label('Meta Title')
                                    ->required(),
                                Forms\Components\Textarea::make('meta_description')
                                    ->label('Meta Description'),
                                Forms\Components\TagsInput::make('meta_keywords')
                                    ->label('Meta Keywords'),
                            ]),
                        Tab::make('Email')
                            ->schema([
                                Forms\Components\TextInput::make('support_email')
                                    ->label('Support Email')
                                    ->email()
                                    ->required(),
                                Forms\Components\TextInput::make('smtp_host')
                                    ->label('SMTP Host'),
                            ]),
                        Tab::make('Theme')
                            ->schema([
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
                            ]),
                        Tab::make('Social')
                            ->schema([
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
                            ]),
                        Tab::make('Analytics')
                            ->schema([
                                Forms\Components\TextInput::make('ga_id')
                                    ->label('Google Analytics ID')
                                    ->placeholder('UA-XXXXX-Y'),
                                Forms\Components\TextInput::make('pixel_id')
                                    ->label('Facebook Pixel ID')
                                    ->placeholder('XXXXXXXXXXXXXXX'),
                            ]),
                    ])
                    ->columnSpan('full'),
            ]);
    }
}
