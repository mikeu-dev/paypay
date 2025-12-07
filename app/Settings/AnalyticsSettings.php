<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class AnalyticsSettings extends Settings
{
    public ?string $ga_id;
    public ?string $pixel_id;

    public static function group(): string
    {
        return 'analytics';
    }
}
