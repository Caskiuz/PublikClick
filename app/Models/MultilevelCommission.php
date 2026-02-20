<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MultilevelCommission extends Model
{
    protected $fillable = [
        'user_id',
        'from_user_id',
        'level',
        'amount',
        'type',
        'description'
    ];

    protected $casts = [
        'amount' => 'decimal:2'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function fromUser()
    {
        return $this->belongsTo(User::class, 'from_user_id');
    }

    // Comisiones por nivel
    public static function getLevelCommissions()
    {
        return [
            1 => 400, // Nivel 1: $400 COP
            2 => 20,  // Nivel 2: $20 COP
            3 => 30,  // Nivel 3: $30 COP
            4 => 40,  // Nivel 4: $40 COP
            5 => 50,  // Nivel 5: $50 COP
            6 => 60   // Nivel 6: $60 COP
        ];
    }

    public static function payCommission($userId, $fromUserId, $level, $type = 'click')
    {
        $commissions = self::getLevelCommissions();
        $amount = $commissions[$level] ?? 0;

        if ($amount > 0) {
            $commission = self::create([
                'user_id' => $userId,
                'from_user_id' => $fromUserId,
                'level' => $level,
                'amount' => $amount,
                'type' => $type,
                'description' => "Comisi\u00f3n nivel {$level} por {$type}"
            ]);

            // Agregar a billetera del usuario
            $user = User::find($userId);
            if ($user && $user->withdrawalWallet) {
                $user->withdrawalWallet->addFunds($amount, "Comisi\u00f3n multinivel nivel {$level}");
            }

            return $commission;
        }

        return null;
    }
}
