<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class SiteSetting extends Model
{
    protected $fillable = ['key', 'value', 'type', 'group', 'description'];

    public static function get($key, $default = null)
    {
        return Cache::remember("setting_{$key}", 3600, function() use ($key, $default) {
            $setting = self::where('key', $key)->first();
            return $setting ? $setting->value : $default;
        });
    }

    public static function set($key, $value)
    {
        $setting = self::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );
        
        Cache::forget("setting_{$key}");
        return $setting;
    }

    public static function getByGroup($group)
    {
        return self::where('group', $group)->get();
    }
}
