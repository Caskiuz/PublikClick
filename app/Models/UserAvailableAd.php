<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class UserAvailableAd extends Model
{
    protected $fillable = [
        'user_id',
        'ad_type',
        'quantity',
        'expires_at',
        'generated_date'
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'generated_date' => 'date',
        'quantity' => 'integer'
    ];

    const TYPE_PRINCIPAL = 'principal';
    const TYPE_MINI = 'mini';
    const TYPE_MEGA = 'mega';
    const TYPE_MINI_UNLOCKED = 'mini_unlocked';

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Generar anuncios principales (NO acumulables, 24h)
    public static function generatePrincipalAds($userId)
    {
        $user = User::with('activePackage')->find($userId);
        if (!$user || !$user->activePackage) return;

        // Eliminar anuncios principales anteriores
        self::where('user_id', $userId)
            ->where('ad_type', self::TYPE_PRINCIPAL)
            ->delete();

        // Crear 5 anuncios principales nuevos
        self::create([
            'user_id' => $userId,
            'ad_type' => self::TYPE_PRINCIPAL,
            'quantity' => 5,
            'expires_at' => now()->addDay(),
            'generated_date' => today()
        ]);
    }

    // Generar mini-anuncios (acumulables, 30 días)
    public static function generateMiniAds($userId)
    {
        $user = User::with('activePackage')->find($userId);
        if (!$user || !$user->activePackage) return;

        $quantity = match($user->activePackage->price) {
            25 => 4,
            50 => 4,
            100 => 4,
            150 => 8,
            default => 4
        };

        self::create([
            'user_id' => $userId,
            'ad_type' => self::TYPE_MINI,
            'quantity' => $quantity,
            'expires_at' => now()->addDays(30),
            'generated_date' => today()
        ]);
    }

    // Generar mega-anuncios por compra de referido (acumulables, 30 días)
    public static function generateMegaAds($userId, $packagePrice)
    {
        $quantity = match($packagePrice) {
            25 => 5,
            50 => 10,
            100 => 20,
            150 => 30,
            default => 5
        };

        self::create([
            'user_id' => $userId,
            'ad_type' => self::TYPE_MEGA,
            'quantity' => $quantity,
            'expires_at' => now()->addDays(30),
            'generated_date' => today()
        ]);
    }

    // Generar mini-anuncios desbloqueados por categoría (acumulables, 30 días)
    public static function generateUnlockedMiniAds($userId, $referredUserId)
    {
        $user = User::with('currentRank')->find($userId);
        if (!$user || !$user->currentRank) return;

        $quantity = match($user->currentRank->name) {
            'Jade' => 1,
            'Perla' => 2,
            'Zafiro' => 3,
            'Rubí' => 4,
            default => 5 // Esmeralda+
        };

        self::create([
            'user_id' => $userId,
            'ad_type' => self::TYPE_MINI_UNLOCKED,
            'quantity' => $quantity,
            'expires_at' => now()->addDays(30),
            'generated_date' => today()
        ]);
    }

    // Obtener total disponible por tipo
    public static function getTotalAvailable($userId, $adType)
    {
        return self::where('user_id', $userId)
            ->where('ad_type', $adType)
            ->where('expires_at', '>', now())
            ->sum('quantity');
    }

    // Consumir un anuncio
    public static function consumeAd($userId, $adType)
    {
        $ad = self::where('user_id', $userId)
            ->where('ad_type', $adType)
            ->where('quantity', '>', 0)
            ->where('expires_at', '>', now())
            ->orderBy('expires_at', 'asc')
            ->first();

        if (!$ad) {
            throw new \Exception('No hay anuncios disponibles de este tipo');
        }

        $ad->quantity--;
        $ad->save();

        if ($ad->quantity == 0) {
            $ad->delete();
        }

        return true;
    }

    // Limpiar anuncios expirados
    public static function cleanExpired()
    {
        self::where('expires_at', '<=', now())->delete();
    }
}
