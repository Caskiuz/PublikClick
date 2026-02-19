<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'user_id',
        'wallet_id',
        'type',
        'amount',
        'description',
        'reference_id',
        'status',
        'payment_method',
        'payment_details',
        'processed_at',
        'admin_notes',
        'user_comment',
        'commented_at',
        'requires_comment',
        'proof_image',
        'processed_by'
    ];

    protected $casts = [
        'amount' => 'decimal:4',
        'payment_details' => 'array',
        'processed_at' => 'datetime',
        'commented_at' => 'datetime',
        'requires_comment' => 'boolean'
    ];

    const TYPE_CLICK_EARNING = 'click_earning';
    const TYPE_REFERRAL_COMMISSION = 'referral_commission';
    const TYPE_PACKAGE_PURCHASE = 'package_purchase';
    const TYPE_WITHDRAWAL = 'withdrawal';
    const TYPE_DEPOSIT = 'deposit';

    const STATUS_PENDING = 'pending';
    const STATUS_COMPLETED = 'completed';
    const STATUS_FAILED = 'failed';

    // Relaciones
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function processedBy()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    // Métodos de negocio
    public static function recordClickEarning($userId, $amount, $adId)
    {
        return self::create([
            'user_id' => $userId,
            'type' => self::TYPE_CLICK_EARNING,
            'amount' => $amount,
            'description' => "Ganancia por click en anuncio #{$adId}",
            'reference_id' => $adId,
            'status' => self::STATUS_COMPLETED
        ]);
    }

    public static function recordReferralCommission($userId, $amount, $referredUserId, $level)
    {
        return self::create([
            'user_id' => $userId,
            'type' => self::TYPE_REFERRAL_COMMISSION,
            'amount' => $amount,
            'description' => "Comisión nivel {$level} por referido #{$referredUserId}",
            'reference_id' => $referredUserId,
            'status' => self::STATUS_COMPLETED
        ]);
    }
}
