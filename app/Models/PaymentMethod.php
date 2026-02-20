<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'icon',
        'is_active',
        'config',
        'min_withdrawal',
        'instructions'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'config' => 'array'
    ];

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public static function getActive()
    {
        return self::where('is_active', true)->orderBy('name')->get();
    }

    public static function getBySlug($slug)
    {
        return self::where('slug', $slug)->where('is_active', true)->first();
    }
}
