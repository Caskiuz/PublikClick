<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Withdrawal extends Model
{
    protected $table = 'transactions';

    protected $fillable = [
        'user_id', 'wallet_id', 'type', 'amount', 'description',
        'status', 'payment_method', 'payment_details', 'processed_at', 'admin_notes'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'processed_at' => 'datetime',
        'payment_details' => 'array'
    ];

    const STATUS_PENDING = 'pending';
    const STATUS_COMPLETED = 'completed';
    const STATUS_REJECTED = 'rejected';

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function wallet()
    {
        return $this->belongsTo(Wallet::class);
    }

    public static function canUserWithdraw($userId)
    {
        $user = User::with(['currentRank', 'activePackage', 'wallets'])->find($userId);
        
        if (!$user || !$user->activePackage) {
            return ['can' => false, 'reason' => 'Necesitas un paquete activo'];
        }

        // Verificar último retiro (30 días)
        $lastWithdrawal = self::where('user_id', $userId)
            ->where('type', 'debit')
            ->where('status', self::STATUS_COMPLETED)
            ->latest()
            ->first();

        if ($lastWithdrawal && $lastWithdrawal->processed_at->diffInDays(now()) < 30) {
            $daysLeft = 30 - $lastWithdrawal->processed_at->diffInDays(now());
            return ['can' => false, 'reason' => "Debes esperar {$daysLeft} días más"];
        }

        // Verificar invitado activo
        $activeReferrals = $user->referrals()->whereHas('activePackage')->count();
        if ($activeReferrals < 1) {
            return ['can' => false, 'reason' => 'Necesitas al menos 1 invitado activo'];
        }

        return ['can' => true];
    }

    public static function getMinimumWithdrawal($rankName)
    {
        return match($rankName) {
            'Jade' => ['usd' => 29, 'cop' => 110000],
            'Perla' => ['usd' => 53, 'cop' => 200000],
            'Zafiro' => ['usd' => 106, 'cop' => 400000],
            'Rubí' => ['usd' => 346, 'cop' => 1300000],
            'Esmeralda' => ['usd' => 398, 'cop' => 1500000],
            default => ['usd' => 0, 'cop' => 0]
        };
    }
}
