<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserAdClick extends Model
{
    protected $fillable = [
        'user_id',
        'ad_id',
        'mega_ad_id',
        'ad_type', // 'main', 'mini', 'mega'
        'clicked_at',
        'earnings',
        'ip_address',
        'user_agent',
        'is_valid'
    ];

    protected $casts = [
        'clicked_at' => 'datetime',
        'earnings' => 'decimal:2',
        'is_valid' => 'boolean'
    ];

    // Relaciones
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function ad()
    {
        return $this->belongsTo(Ad::class);
    }

    public function megaAd()
    {
        return $this->belongsTo(MegaAd::class);
    }

    // Métodos de negocio
    public function isFromToday()
    {
        return $this->clicked_at->isToday();
    }

    public function markAsInvalid($reason = null)
    {
        $this->is_valid = false;
        $this->save();
        
        // Revertir ganancias si es necesario
        if ($this->earnings > 0) {
            $this->revertEarnings($reason);
        }
    }

    private function revertEarnings($reason)
    {
        // Lógica para revertir ganancias en caso de click fraudulento
        $wallet = $this->user->withdrawalWallet;
        if ($wallet && $wallet->balance >= $this->earnings) {
            $wallet->balance -= $this->earnings;
            $wallet->save();
            
            // Crear transacción de reversión
            $wallet->transactions()->create([
                'user_id' => $this->user_id,
                'type' => 'debit',
                'amount' => $this->earnings,
                'description' => "Reversión por click inválido: {$reason}",
                'status' => 'completed'
            ]);
        }
    }

    // Validaciones anti-fraude
    public static function validateClick($userId, $ipAddress, $userAgent)
    {
        // Verificar clicks múltiples desde la misma IP en poco tiempo
        $recentClicks = self::where('ip_address', $ipAddress)
                           ->where('created_at', '>', now()->subMinutes(5))
                           ->count();
        
        if ($recentClicks >= 3) {
            return ['valid' => false, 'reason' => 'Demasiados clicks desde la misma IP'];
        }
        
        // Verificar clicks del mismo usuario en poco tiempo
        $userRecentClicks = self::where('user_id', $userId)
                               ->where('created_at', '>', now()->subMinutes(1))
                               ->count();
        
        if ($userRecentClicks >= 2) {
            return ['valid' => false, 'reason' => 'Clicks demasiado rápidos'];
        }
        
        return ['valid' => true];
    }

    public static function recordClick($userId, $adId, $earnings, $adType = 'main')
    {
        return self::create([
            'user_id' => $userId,
            'ad_id' => $adId,
            'ad_type' => $adType,
            'clicked_at' => now(),
            'earnings' => $earnings,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'is_valid' => true
        ]);
    }
}
