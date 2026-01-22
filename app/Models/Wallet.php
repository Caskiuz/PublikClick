<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    const TYPE_WITHDRAWAL = 'withdrawal';
    const TYPE_DONATION = 'donation';

    protected $fillable = [
        'user_id',
        'type',
        'balance',
        'total_earned',
        'total_withdrawn'
    ];

    protected $casts = [
        'balance' => 'decimal:2',
        'total_earned' => 'decimal:2',
        'total_withdrawn' => 'decimal:2'
    ];

    // Relaciones
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    // Métodos de negocio
    public function addFunds($amount, $description = null)
    {
        $this->balance += $amount;
        $this->total_earned += $amount;
        $this->save();

        // Crear transacción
        $this->transactions()->create([
            'user_id' => $this->user_id,
            'type' => 'credit',
            'amount' => $amount,
            'description' => $description ?? "Fondos agregados a cartera {$this->type}",
            'status' => 'completed'
        ]);

        return $this;
    }

    public function withdrawFunds($amount, $description = null)
    {
        if ($this->balance < $amount) {
            throw new \Exception('Fondos insuficientes');
        }

        $this->balance -= $amount;
        $this->total_withdrawn += $amount;
        $this->save();

        // Crear transacción
        $this->transactions()->create([
            'user_id' => $this->user_id,
            'type' => 'debit',
            'amount' => $amount,
            'description' => $description ?? "Retiro de cartera {$this->type}",
            'status' => 'pending'
        ]);

        return $this;
    }

    public static function createUserWallets($userId)
    {
        self::create([
            'user_id' => $userId,
            'type' => self::TYPE_WITHDRAWAL,
            'balance' => 0,
            'total_earned' => 0,
            'total_withdrawn' => 0
        ]);

        self::create([
            'user_id' => $userId,
            'type' => self::TYPE_DONATION,
            'balance' => 0,
            'total_earned' => 0,
            'total_withdrawn' => 0
        ]);
    }
}