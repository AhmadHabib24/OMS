<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

class SettingService
{
    public function get(string $key, $default = null)
    {
        $settings = $this->all();

        return $settings[$key] ?? $default;
    }

    public function set(string $group, string $key, $value, string $type = 'string'): void
    {
        Setting::updateOrCreate(
            ['key' => $key],
            [
                'group' => $group,
                'value' => $this->prepareValue($value, $type),
                'type' => $type,
            ]
        );

        $this->clearCache();
    }

    public function setMany(array $items): void
    {
        foreach ($items as $item) {
            $this->set(
                $item['group'],
                $item['key'],
                $item['value'],
                $item['type'] ?? 'string'
            );
        }
    }

    public function all(): array
    {
        return Cache::rememberForever('app_settings', function () {
            return Setting::all()
                ->mapWithKeys(function ($setting) {
                    return [$setting->key => $setting->casted_value];
                })
                ->toArray();
        });
    }

    public function group(string $group): array
    {
        return Cache::rememberForever("app_settings_group_{$group}", function () use ($group) {
            return Setting::where('group', $group)
                ->get()
                ->mapWithKeys(function ($setting) {
                    return [$setting->key => $setting->casted_value];
                })
                ->toArray();
        });
    }

    public function clearCache(): void
    {
        Cache::forget('app_settings');

        $groups = Setting::distinct()->pluck('group');
        foreach ($groups as $group) {
            Cache::forget("app_settings_group_{$group}");
        }
    }

    protected function prepareValue($value, string $type): ?string
    {
        if ($value === null) {
            return null;
        }

        return match ($type) {
            'boolean' => $value ? '1' : '0',
            'integer' => (string) (int) $value,
            'json' => json_encode($value),
            default => (string) $value,
        };
    }
}