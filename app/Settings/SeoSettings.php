<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class SeoSettings extends Settings
{
    public ?string $meta_title;
    public ?string $meta_description;
    public ?array $meta_keywords;

    public static function group(): string
    {
        return 'seo';
    }
}
