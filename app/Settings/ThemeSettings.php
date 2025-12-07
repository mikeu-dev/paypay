<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class ThemeSettings extends Settings
{
    public string $primary_color;
    public ?string $logo_url;
    public ?string $favicon_url;
    public string $navigation_layout;
    public string $font_family;

    public static function group(): string
    {
        return 'theme';
    }
}
