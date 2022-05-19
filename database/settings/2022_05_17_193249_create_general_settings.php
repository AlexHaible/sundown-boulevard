<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

class CreateGeneralSettings extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('general.restaurant_name', 'Sundown Boulevard');
        $this->migrator->add('general.opens_at', '16:00');
        $this->migrator->add('general.closes_at', '22:00');
        $this->migrator->add('general.tables', 10);
        $this->migrator->add('general.intervals', 'PT1H');
    }
}
