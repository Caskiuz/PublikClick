<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rank extends Model
{
    protected $fillable = [
        'name',
        'min_referrals',
        'max_referrals',
        'mega_ads_monthly',
        'referral_commission',
        'mini_ads_daily',
        'order'
    ];

    protected $casts = [
        'referral_commission' => 'decimal:2',
        'mega_ads_monthly' => 'integer',
        'mini_ads_daily' => 'integer',
        'min_referrals' => 'integer',
        'max_referrals' => 'integer',
        'order' => 'integer'
    ];

    // Relaciones
    public function users()
    {
        return $this->hasMany(User::class, 'current_rank_id');
    }

    // Métodos estáticos para obtener rangos
    public static function getRankByReferrals($referralCount)
    {
        return self::where('min_referrals', '<=', $referralCount)
                   ->where(function($query) use ($referralCount) {
                       $query->where('max_referrals', '>=', $referralCount)
                             ->orWhereNull('max_referrals');
                   })
                   ->orderBy('order', 'desc')
                   ->first();
    }

    public static function seedRanks()
    {
        $config = \App\Services\EconomicConfig::class;
        $ranks = [
            ['name' => 'Jade', 'min_referrals' => 0, 'max_referrals' => 2, 'mega_ads_monthly' => 10, 'referral_commission' => $config::getRankCommission('Jade'), 'mini_ads_daily' => $config::getUnlockedMinisCount('Jade'), 'order' => 1],
            ['name' => 'Perla', 'min_referrals' => 3, 'max_referrals' => 5, 'mega_ads_monthly' => 25, 'referral_commission' => $config::getRankCommission('Perla'), 'mini_ads_daily' => $config::getUnlockedMinisCount('Perla'), 'order' => 2],
            ['name' => 'Zafiro', 'min_referrals' => 6, 'max_referrals' => 9, 'mega_ads_monthly' => 50, 'referral_commission' => $config::getRankCommission('Zafiro'), 'mini_ads_daily' => $config::getUnlockedMinisCount('Zafiro'), 'order' => 3],
            ['name' => 'Rubí', 'min_referrals' => 10, 'max_referrals' => 19, 'mega_ads_monthly' => 75, 'referral_commission' => $config::getRankCommission('Rubí'), 'mini_ads_daily' => $config::getUnlockedMinisCount('Rubí'), 'order' => 4],
            ['name' => 'Esmeralda', 'min_referrals' => 20, 'max_referrals' => 25, 'mega_ads_monthly' => 125, 'referral_commission' => $config::getRankCommission('Esmeralda'), 'mini_ads_daily' => $config::getUnlockedMinisCount('Esmeralda'), 'order' => 5],
            ['name' => 'Diamante', 'min_referrals' => 26, 'max_referrals' => 30, 'mega_ads_monthly' => 150, 'referral_commission' => $config::getRankCommission('Diamante'), 'mini_ads_daily' => $config::getUnlockedMinisCount('Diamante'), 'order' => 6],
            ['name' => 'Diamante Azul', 'min_referrals' => 31, 'max_referrals' => 35, 'mega_ads_monthly' => 175, 'referral_commission' => $config::getRankCommission('Diamante Azul'), 'mini_ads_daily' => $config::getUnlockedMinisCount('Diamante Azul'), 'order' => 7],
            ['name' => 'Diamante Negro', 'min_referrals' => 36, 'max_referrals' => 39, 'mega_ads_monthly' => 190, 'referral_commission' => $config::getRankCommission('Diamante Negro'), 'mini_ads_daily' => $config::getUnlockedMinisCount('Diamante Negro'), 'order' => 8],
            ['name' => 'Diamante Corona', 'min_referrals' => 40, 'max_referrals' => null, 'mega_ads_monthly' => 200, 'referral_commission' => $config::getRankCommission('Diamante Corona'), 'mini_ads_daily' => $config::getUnlockedMinisCount('Diamante Corona'), 'order' => 9],
        ];

        foreach ($ranks as $rank) {
            self::updateOrCreate(['name' => $rank['name']], $rank);
        }
    }
}