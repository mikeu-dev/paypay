<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('theme.navigation_layout', 'sidebar');
        $this->migrator->add('theme.font_family', 'Inter');
    }

    public function down(): void
    {
        $this->migrator->delete('theme.navigation_layout');
        $this->migrator->delete('theme.font_family');
    }
};
