<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class AvailableAd extends Model
{
    protected $fillable = [
        'user_id',
        'ad_type',
        'generated_at',
        'expires_at',
        'is_used',
        'used_at'
    ];

    protected $casts = [
        'generated_at' => 'datetime',
        'expires_at' => 'datetime',
        'used_at' => 'datetime',
        'is_used' => 'boolean'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function generateForUser($userId, $adType, $quantity = 1)
    {
        $expiresAt = match($adType) {
            'main' => now()->endOfDay(),
            'mini', 'mega' => now()->addDays(30),
        };

        $ads = [];
        for ($i = 0; $i < $quantity; $i++) {
            $ads[] = self::create([
                'user_id' => $userId,
                'ad_type' => $adType,
                'generated_at' => now(),
                'expires_at' => $expiresAt,
                'is_used' => false
            ]);
        }

        return $ads;
    }

    public static function getAvailableCount($userId, $adType)
    {
        return self::where('user_id', $userId)
            ->where('ad_type', $adType)
            ->where('is_used', false)
            ->where('expires_at', '>', now())
            ->count();
    }

    public static function markAsUsed($userId, $adType)
    {
        $ad = self::where('user_id', $userId)
            ->where('ad_type', $adType)
            ->where('is_used', false)
            ->where('expires_at', '>', now())
            ->oldest('generated_at')
            ->first();

        if ($ad) {
            $ad->update([
                'is_used' => true,
                'used_at' => now()
            ]);
            return true;
        }

        return false;
    }

    public static function cleanExpired()
    {
        return self::where('expires_at', '<', now())->delete();
    }
}
