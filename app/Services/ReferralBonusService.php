<?php

namespace App\Services;

use App\Models\User;
use App\Models\AvailableAd;

class ReferralBonusService
{
    /**
     * Otorgar mega-anuncios al referidor cuando su referido compra un paquete
     */
    public static function grantMegaAdsForPurchase($referredUserId, $packagePriceUsd)
    {
        $referredUser = User::find($referredUserId);
        
        if (!$referredUser || !$referredUser->referred_by) {
            return;
        }
        
        $referrer = User::find($referredUser->referred_by);
        
        if (!$referrer) {
            return;
        }
        
        // Cantidad de mega-anuncios según paquete comprado (SISTEMA_ECONOMICO.md)
        $megaAdsQuantity = match((int)$packagePriceUsd) {
            25 => 5,    // $25 USD = 5 mega-anuncios
            50 => 10,   // $50 USD = 10 mega-anuncios
            100 => 20,  // $100 USD = 20 mega-anuncios
            150 => 30,  // $150 USD = 30 mega-anuncios
            default => 5
        };
        
        // Generar mega-anuncios para el referidor
        AvailableAd::generateForUser($referrer->id, 'mega', $megaAdsQuantity);
        
        // Notificar al referidor
        \Log::info("Mega-anuncios otorgados: {$megaAdsQuantity} para usuario {$referrer->name} por compra de {$referredUser->name}");
        
        return $megaAdsQuantity;
    }
}
