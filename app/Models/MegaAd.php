<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class MegaAd extends Model
{
    protected $fillable = [
        'user_id',
        'rank_id',
        'month',
        'year',
        'total_available',
        'clicks_used',
        'clicks_remaining',
        'earnings_per_click'
    ];

    protected $casts = [
        'total_available' => 'integer',
        'clicks_used' => 'integer',
        'clicks_remaining' => 'integer',
        'earnings_per_click' => 'decimal:2',
        'month' => 'integer',
        'year' => 'integer'
    ];

    // Relaciones
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function rank()
    {
        return $this->belongsTo(Rank::class);
    }

    public function clicks()
    {
        return $this->hasMany(UserAdClick::class, 'mega_ad_id');
    }

    // Métodos de negocio
    public static function getOrCreateForUser($userId, $month = null, $year = null)
    {
        $month = $month ?? now()->month;
        $year = $year ?? now()->year;

        $user = User::with('currentRank')->find($userId);
        if (!$user) {
            return null;
        }

        // Si no tiene rango, asignar Jade por defecto
        if (!$user->currentRank) {
            $jadeRank = \App\Models\Rank::where('name', 'Jade')->first();
            if ($jadeRank) {
                $user->current_rank_id = $jadeRank->id;
                $user->save();
                $user->load('currentRank');
            }
        }

        if (!$user->currentRank) {
            return null;
        }

        return self::firstOrCreate([
            'user_id' => $userId,
            'month' => (int)$month,
            'year' => (int)$year
        ], [
            'rank_id' => $user->current_rank_id,
            'total_available' => $user->currentRank->mega_ads_monthly ?? 10,
            'clicks_used' => 0,
            'clicks_remaining' => $user->currentRank->mega_ads_monthly ?? 10,
            'earnings_per_click' => 2000.00
        ]);
    }

    public function canClick()
    {
        return $this->clicks_remaining > 0;
    }

    public function recordClick()
    {
        if (!$this->canClick()) {
            throw new \Exception('No hay mega-anuncios disponibles este mes');
        }

        $this->clicks_used++;
        $this->clicks_remaining--;
        $this->save();

        // Crear registro de click
        $click = $this->clicks()->create([
            'user_id' => $this->user_id,
            'ad_id' => null, // Mega ads no tienen ad_id específico
            'clicked_at' => now(),
            'earnings' => $this->earnings_per_click,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent()
        ]);

        // Agregar ganancias a cartera de retiro
        $withdrawalWallet = $this->user->wallets()->where('type', Wallet::TYPE_WITHDRAWAL)->first();
        if ($withdrawalWallet) {
            $withdrawalWallet->addFunds($this->earnings_per_click, "Mega-anuncio clickeado");
        }

        return $click;
    }

    public static function resetMonthlyCounters()
    {
        $currentMonth = now()->month;
        $currentYear = now()->year;

        // Actualizar todos los mega ads del mes actual
        $users = User::with('currentRank')->get();
        
        foreach ($users as $user) {
            if ($user->currentRank) {
                self::updateOrCreate([
                    'user_id' => $user->id,
                    'month' => $currentMonth,
                    'year' => $currentYear
                ], [
                    'rank_id' => $user->current_rank_id,
                    'total_available' => $user->currentRank->mega_ads_monthly,
                    'clicks_used' => 0,
                    'clicks_remaining' => $user->currentRank->mega_ads_monthly,
                    'earnings_per_click' => 2000.00
                ]);
            }
        }
    }
}