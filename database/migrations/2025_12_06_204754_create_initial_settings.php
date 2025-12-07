<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        // General
        $this->migrator->add('general.site_name', 'PayPay Payroll');
        $this->migrator->add('general.site_active', true);

        // SEO
        $this->migrator->add('seo.meta_title', 'PayPay Application');
        $this->migrator->add('seo.meta_description', 'Employee Payroll Management System');
        $this->migrator->add('seo.meta_keywords', ['payroll', 'hr', 'management']);

        // Analytics
        $this->migrator->add('analytics.ga_id', null);
        $this->migrator->add('analytics.pixel_id', null);

        // Email
        $this->migrator->add('email.support_email', 'support@example.com');
        $this->migrator->add('email.smtp_host', 'smtp.mailtrap.io');

        // Social
        $this->migrator->add('social.facebook_url', null);
        $this->migrator->add('social.twitter_url', null);
        $this->migrator->add('social.instagram_url', null);
        $this->migrator->add('social.linkedin_url', null);

        // Theme
        $this->migrator->add('theme.primary_color', '#007bff');
        $this->migrator->add('theme.logo_url', null);
        $this->migrator->add('theme.favicon_url', null);
    }

    public function down(): void
    {
        Schema::dropIfExists('initial_settings'); // Not really correct for SettingsMigration but Spatie handles it
    }
};
