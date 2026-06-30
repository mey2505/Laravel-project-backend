<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

class SettingService
{
    private const CACHE_KEY = 'app_settings';
    private const CACHE_TTL = 3600; // 1 hour

    public function all(): array
    {
        return Cache::remember(self::CACHE_KEY, self::CACHE_TTL, function () {
            return Setting::pluck('value', 'key')->toArray();
        });
    }

    public function get(string $key, mixed $default = null): mixed
    {
        return $this->all()[$key] ?? $default;
    }

    public function set(string $key, mixed $value): void
    {
        Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        Cache::forget(self::CACHE_KEY);
    }

    public function bulkUpdate(array $data): void
    {
        foreach ($data as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }
        Cache::forget(self::CACHE_KEY);
    }
}
