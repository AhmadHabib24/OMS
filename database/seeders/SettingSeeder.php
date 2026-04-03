<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            ['group' => 'general', 'key' => 'site_name', 'value' => 'Office Management System', 'type' => 'string'],
            ['group' => 'general', 'key' => 'site_tagline', 'value' => 'Manage employees, attendance, CRM and campaigns', 'type' => 'string'],

            ['group' => 'ai', 'key' => 'gemini_api_key', 'value' => '', 'type' => 'string'],
            ['group' => 'ai', 'key' => 'gemini_model', 'value' => 'gemini-2.5-flash-lite', 'type' => 'string'],
            ['group' => 'ai', 'key' => 'ai_enabled', 'value' => '1', 'type' => 'boolean'],

            ['group' => 'email', 'key' => 'smtp_host', 'value' => '', 'type' => 'string'],
            ['group' => 'email', 'key' => 'smtp_port', 'value' => '587', 'type' => 'string'],
            ['group' => 'email', 'key' => 'smtp_username', 'value' => '', 'type' => 'string'],
            ['group' => 'email', 'key' => 'smtp_password', 'value' => '', 'type' => 'string'],

            ['group' => 'features', 'key' => 'attendance_module_enabled', 'value' => '1', 'type' => 'boolean'],
            ['group' => 'features', 'key' => 'leave_module_enabled', 'value' => '1', 'type' => 'boolean'],
            ['group' => 'features', 'key' => 'lead_module_enabled', 'value' => '1', 'type' => 'boolean'],
            ['group' => 'features', 'key' => 'campaign_module_enabled', 'value' => '1', 'type' => 'boolean'],
            [
                'group' => 'general',
                'key' => 'site_name',
                'value' => 'Office Management System',
                'type' => 'string',
            ],
            [
                'group' => 'general',
                'key' => 'site_tagline',
                'value' => 'Manage employees, attendance, CRM and campaigns',
                'type' => 'string',
            ],
            [
                'group' => 'general',
                'key' => 'footer_text',
                'value' => '© Office Management System. All rights reserved.',
                'type' => 'string',
            ],

            [
                'group' => 'attendance',
                'key' => 'late_after_minutes',
                'value' => '15',
                'type' => 'integer',
            ],
            [
                'group' => 'attendance',
                'key' => 'half_day_after_minutes',
                'value' => '240',
                'type' => 'integer',
            ],
            [
                'group' => 'attendance',
                'key' => 'require_selfie',
                'value' => '1',
                'type' => 'boolean',
            ],
            [
                'group' => 'attendance',
                'key' => 'require_location',
                'value' => '1',
                'type' => 'boolean',
            ],
            [
                'group' => 'attendance',
                'key' => 'require_checkout',
                'value' => '1',
                'type' => 'boolean',
            ],

            [
                'group' => 'features',
                'key' => 'attendance_module_enabled',
                'value' => '1',
                'type' => 'boolean',
            ],
            [
                'group' => 'features',
                'key' => 'leave_module_enabled',
                'value' => '1',
                'type' => 'boolean',
            ],
            [
                'group' => 'features',
                'key' => 'lead_module_enabled',
                'value' => '1',
                'type' => 'boolean',
            ],
            [
                'group' => 'features',
                'key' => 'campaign_module_enabled',
                'value' => '1',
                'type' => 'boolean',
            ],
            [
                'group' => 'features',
                'key' => 'ai_module_enabled',
                'value' => '1',
                'type' => 'boolean',
            ],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}