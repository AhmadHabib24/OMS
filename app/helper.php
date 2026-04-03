<?php

use App\Services\SettingService;

if (!function_exists('app_setting')) {
    function app_setting(string $key, $default = null)
    {
        return app(SettingService::class)->get($key, $default);
    }
}

if (!function_exists('feature_enabled')) {
    function feature_enabled(string $key, bool $default = true): bool
    {
        return (bool) app_setting($key, $default);
    }
}