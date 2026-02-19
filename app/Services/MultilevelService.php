<?php

namespace App\Services;

use App\Models\User;
use App\Models\MultilevelCommission;
use App\Models\Wallet;

class MultilevelService
{
    // Calcular y asignar estrellas según invitados directos con 40+ invitados
    public static function updateStars($userId)
    {
        $user = User::with('referrals.referrals')->find($userId);
        if (!$user) return;

        // Contar cuántos invitados directos tienen 40+ invitados activos
        $directsWithDiamondCrown = $user->referrals()
            ->whereHas('referrals', function($q) {
                $q->whereHas('activePackage')
                  ->whereHas('adClicks', function($q2) {
                      $q2->where('clicked_at', '>=', now()->subDays(7));
                  });
            }, '>=', 40)
            ->count();

        $stars = min($directsWithDiamondCrown, 5);
        
        if ($user->stars != $stars) {
            $user->stars = $stars;
            $user->save();
        }

        return $stars;
    }

    // Procesar comisiones multinivel cuando un usuario hace click
    public static function processMultilevelCommissions($userId)
    {
        $user = User::find($userId);
        if (!$user || !$user->referred_by) return;

        // Obtener cadena de referidores
        $chain = self::getReferralChain($user->referred_by, 6);

        foreach ($chain as $level => $referrerId) {
            $referrer = User::with('currentRank')->find($referrerId);
            if (!$referrer || !$referrer->activePackage) continue;

            // Verificar si tiene las estrellas necesarias
            $requiredStars = $level - 1; // Nivel 2 = 1 estrella, Nivel 3 = 2 estrellas, etc.
            if ($referrer->stars < $requiredStars) continue;

            // Verificar que esté en Diamante Corona
            if ($referrer->currentRank->name !== 'DIAMANTE CORONA') continue;

            // Calcular comisión según nivel
            $commission = match($level) {
                2 => 20,  // $20 COP por click nivel 2
                3 => 30,  // $30 COP por click nivel 3
                4 => 40,  // $40 COP por click nivel 4
                5 => 50,  // $50 COP por click nivel 5
                6 => 60,  // $60 COP por click nivel 6
                default => 0
            };

            if ($commission > 0) {
                // Registrar comisión
                MultilevelCommission::create([
                    'user_id' => $referrerId,
                    'from_user_id' => $userId,
                    'level' => $level,
                    'amount' => $commission,
                    'description' => "Comisión nivel {$level} por click de usuario #{$userId}"
                ]);

                // Agregar a billetera
                $wallet = $referrer->wallets()->where('type', Wallet::TYPE_WITHDRAWAL)->first();
                if ($wallet) {
                    $wallet->addFunds($commission, "Comisión multinivel nivel {$level}");
                }
            }
        }
    }

    // Obtener cadena de referidores hasta N niveles
    private static function getReferralChain($userId, $maxLevels)
    {
        $chain = [];
        $currentUserId = $userId;
        $level = 2; // Empezamos desde nivel 2

        while ($currentUserId && $level <= $maxLevels) {
            $chain[$level] = $currentUserId;
            
            $user = User::find($currentUserId);
            $currentUserId = $user ? $user->referred_by : null;
            $level++;
        }

        return $chain;
    }

    // Obtener estadísticas de multinivel para un usuario
    public static function getMultilevelStats($userId)
    {
        $stats = [];
        
        for ($level = 2; $level <= 6; $level++) {
            $users = self::getUsersAtLevel($userId, $level);
            $monthlyEarnings = MultilevelCommission::where('user_id', $userId)
                ->where('level', $level)
                ->whereMonth('created_at', now()->month)
                ->sum('amount');

            $stats[$level] = [
                'users_count' => count($users),
                'monthly_earnings' => $monthlyEarnings,
                'users' => $users
            ];
        }

        return $stats;
    }

    // Obtener usuarios en un nivel específico
    private static function getUsersAtLevel($userId, $targetLevel)
    {
        if ($targetLevel < 2 || $targetLevel > 6) return [];

        $users = [];
        $currentLevel = [$userId];

        for ($level = 1; $level < $targetLevel; $level++) {
            $nextLevel = [];
            foreach ($currentLevel as $uid) {
                $referrals = User::where('referred_by', $uid)->pluck('id')->toArray();
                $nextLevel = array_merge($nextLevel, $referrals);
            }
            $currentLevel = $nextLevel;
        }

        return User::whereIn('id', $currentLevel)
            ->whereHas('activePackage')
            ->get();
    }
}
