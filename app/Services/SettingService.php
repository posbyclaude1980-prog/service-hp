<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Laravel\Facades\Image;

class SettingService
{
    private const CACHE_KEY = 'app_settings';

    private const LOGO_KEY = 'shop_logo';

    public function get(string $key, mixed $default = null): mixed
    {
        $settings = $this->getAll();

        return $settings->get($key, $default);
    }

    public function set(string $key, mixed $value): Setting
    {
        $setting = Setting::query()->updateOrCreate(
            ['key' => $key],
            ['value' => $value === null ? null : (string) $value],
        );

        Cache::forget(self::CACHE_KEY);

        return $setting;
    }

    public function getAll(): Collection
    {
        $cached = Cache::get(self::CACHE_KEY);

        if ($cached instanceof Collection) {
            return $cached;
        }

        if ($cached instanceof \Illuminate\Support\Enumerable) {
            return collect($cached->toArray());
        }

        $settings = Setting::query()
            ->pluck('value', 'key');

        Cache::put(self::CACHE_KEY, $settings, now()->addYears(1));

        return $settings;
    }

    public function uploadLogo(UploadedFile $file, int $maxWidth = 400, int $maxHeight = 400): string
    {
        $filename = 'logo-'.Str::uuid().'.webp';
        $path = 'settings/'.$filename;

        $image = Image::read($file)
            ->scaleDown($maxWidth, $maxHeight)
            ->toWebp(85);

        Storage::disk('public')->put($path, (string) $image);

        $previousLogo = $this->get(self::LOGO_KEY);

        if (is_string($previousLogo) && $previousLogo !== '' && Storage::disk('public')->exists($previousLogo)) {
            Storage::disk('public')->delete($previousLogo);
        }

        $this->set(self::LOGO_KEY, $path);

        return $path;
    }
}
