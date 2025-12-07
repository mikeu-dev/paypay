<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class EmailSettings extends Settings
{
    public ?string $support_email;
    public ?string $smtp_host;

    public static function group(): string
    {
        return 'email';
    }
}
