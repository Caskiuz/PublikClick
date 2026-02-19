<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\AvailableAd;
use Carbon\Carbon;

class GenerateDailyAds extends Command
{
    protected $signature = 'ads:generate-daily';
    protected $description = 'Genera anuncios diarios para todos los usuarios activos';

    public function handle()
    {
        $this->info('Iniciando generación de anuncios diarios...');

        // Limpiar anuncios expirados
        AvailableAd::cleanExpired();
        $this->info('Anuncios expirados eliminados');

        // Obtener usuarios activos con paquete
        $users = User::whereNotNull('current_package_id')
                    ->where('is_active', true)
                    ->with(['currentPackage', 'currentRank'])
                    ->get();

        $this->info("Procesando {$users->count()} usuarios activos...");

        foreach ($users as $user) {
            // 1. Generar 5 anuncios principales (NO acumulables)
            AvailableAd::generateForUser($user->id, 'main', 5);

            // 2. Generar mini-anuncios según paquete (acumulables 30 días)
            $miniAdsCount = $user->currentPackage->mini_ads_count ?? 4;
            AvailableAd::generateForUser($user->id, 'mini', $miniAdsCount);

            // 3. Generar mini-anuncios desbloqueados por referidos
            if ($user->currentRank) {
                $activeReferrals = $user->activeReferrals()->count();
                $miniAdsPerReferral = $user->currentRank->mini_ads_daily ?? 1;
                $bonusMiniAds = $activeReferrals * $miniAdsPerReferral;
                
                if ($bonusMiniAds > 0) {
                    AvailableAd::generateForUser($user->id, 'mini', $bonusMiniAds);
                }
            }

            $this->info("✓ Usuario {$user->name} - Anuncios generados");
        }

        $this->info('✅ Generación de anuncios completada');
        return 0;
    }
}
