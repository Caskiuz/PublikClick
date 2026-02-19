<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\UserAdClick;
use App\Models\Wallet;
use Carbon\Carbon;

class ProcessReferralCommissions extends Command
{
    protected $signature = 'commissions:process';
    protected $description = 'Procesa comisiones por clicks de referidos directos';

    public function handle()
    {
        $this->info('Procesando comisiones de referidos...');

        // Obtener clicks de ayer que aún no han generado comisión
        $yesterday = Carbon::yesterday();
        
        $clicks = UserAdClick::whereDate('clicked_at', $yesterday)
                            ->where('ad_type', 'main')
                            ->where('is_valid', true)
                            ->get();

        $totalCommissions = 0;

        foreach ($clicks as $click) {
            $user = $click->user;
            
            // Si el usuario tiene referidor
            if ($user->referrer) {
                $referrer = $user->referrer;
                
                // Calcular comisión según rango del referidor
                if ($referrer->currentRank) {
                    $commission = $referrer->currentRank->referral_commission;
                    
                    // Agregar comisión a cartera del referidor
                    $wallet = $referrer->withdrawalWallet;
                    if ($wallet) {
                        $wallet->addFunds(
                            $commission, 
                            "Comisión por click de {$user->name}"
                        );
                        
                        $totalCommissions += $commission;
                        $this->info("✓ {$referrer->name} recibió ${commission} por click de {$user->name}");
                    }
                }
            }
        }

        $this->info("✅ Total comisiones procesadas: $" . number_format($totalCommissions, 2));
        return 0;
    }
}
