<?php

namespace App\Services;

use App\Models\User;
use App\Models\Rank;
use App\Models\UserAvailableAd;
use App\Models\Wallet;

class RankService
{
    public static function updateUserRank($userId)
    {
        $user = User::with(['referrals', 'currentRank'])->find($userId);
        if (!$user) return null;

        $activeReferrals = $user->referrals()
            ->whereHas('activePackage')
            ->whereHas('adClicks', function($q) {
                $q->where('clicked_at', '>=', now()->subDays(7));
            })
            ->count();

        $newRank = Rank::getRankByReferrals($activeReferrals);
        
        if ($newRank && $user->current_rank_id !== $newRank->id) {
            $user->current_rank_id = $newRank->id;
            $user->save();
            return ['updated' => true, 'rank' => $newRank, 'active_referrals' => $activeReferrals];
        }

        return ['updated' => false, 'rank' => $user->currentRank, 'active_referrals' => $activeReferrals];
    }

    public static function processReferralCommission($referredUserId, $adType)
    {
        $referredUser = User::find($referredUserId);
        if (!$referredUser || !$referredUser->referred_by) return;

        $referrer = User::with('currentRank')->find($referredUser->referred_by);
        if (!$referrer || !$referrer->currentRank) return;

        $commission = \App\Services\EconomicConfig::getRankCommission($referrer->currentRank->name);

        $referrer->withdrawalWallet->addFunds($commission, "Comisión por click de referido #{$referredUserId}");

        if ($adType === 'principal') {
            UserAvailableAd::generateUnlockedMiniAds($referrer->id, $referredUserId);
        }
    }

    public static function grantMegaAdsForPurchase($referrerId, $packagePrice)
    {
        UserAvailableAd::generateMegaAds($referrerId, $packagePrice);
    }

    public static function getActiveReferralsCount($userId)
    {
        return User::find($userId)->referrals()
            ->whereHas('activePackage')
            ->whereHas('adClicks', function($q) {
                $q->where('clicked_at', '>=', now()->subDays(7));
            })
            ->count();
    }
}
