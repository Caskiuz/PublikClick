<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentGateway extends Model
{
    protected $fillable = ['code', 'name', 'type', 'category', 'description', 'logo', 'is_active', 'config', 'sort_order'];

    protected $casts = [
        'is_active' => 'boolean',
        'config' => 'array'
    ];

    public static function getActive()
    {
        return self::where('is_active', true)->orderBy('sort_order')->get();
    }

    public static function getActiveFiat()
    {
        return self::where('is_active', true)->where('type', 'fiat')->orderBy('sort_order')->get();
    }

    public static function getActiveCrypto()
    {
        return self::where('is_active', true)->where('type', 'crypto')->orderBy('sort_order')->get();
    }

    public function getConfigValue($key, $default = null)
    {
        return $this->config[$key] ?? $default;
    }
}
