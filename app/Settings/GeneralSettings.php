<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class GeneralSettings extends Settings
{
    public string $restaurant_name;
    public string $opens_at;
    public string $closes_at;
    public int $tables;
    public string $intervals;

    public static function group(): string
    {
        return 'general';
    }
}
