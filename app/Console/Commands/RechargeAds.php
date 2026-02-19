<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\UserAvailableAd;

class RechargeAds extends Command
{
    protected $signature = 'ads:recharge';
    protected $description = 'Recarga diaria de anuncios principales y mini-anuncios';

    public function handle()
    {
        $this->info('Iniciando recarga de anuncios...');

        // Limpiar anuncios expirados
        UserAvailableAd::cleanExpired();
        $this->info('✓ Anuncios expirados eliminados');

        // Obtener usuarios con paquete activo
        $users = User::whereHas('activePackage')->get();
        $count = 0;

        foreach ($users as $user) {
            // Generar anuncios principales (NO acumulables)
            UserAvailableAd::generatePrincipalAds($user->id);
            
            // Generar mini-anuncios (acumulables)
            UserAvailableAd::generateMiniAds($user->id);
            
            $count++;
        }

        $this->info("✓ Anuncios recargados para {$count} usuarios");
        $this->info('Recarga completada exitosamente');

        return 0;
    }
}
