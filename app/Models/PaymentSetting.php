<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentSetting extends Model
{
    protected $fillable = [
        'gateway_name',
        'gateway_type',
        'is_active',
        'api_key',
        'api_secret',
        'webhook_secret',
        'wallet_address',
        'network',
        'settings'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'settings' => 'array'
    ];

    protected $hidden = [
        'api_key',
        'api_secret',
        'webhook_secret'
    ];
}
