<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\AvailableAd;

class GenerateMonthlyMegaAds extends Command
{
    protected $signature = 'ads:generate-monthly-mega';
    protected $description = 'Generar mega-anuncios mensuales para todos los usuarios activos';

    public function handle()
    {
        $this->info('Iniciando generación de mega-anuncios mensuales...');

        $users = User::where('is_active', true)
            ->whereNotNull('current_package_id')
            ->with('currentRank')
            ->get();

        $totalGenerated = 0;

        foreach ($users as $user) {
            if ($user->currentRank) {
                $megaAdsCount = $user->currentRank->mega_ads_monthly ?? 10;
                AvailableAd::generateForUser($user->id, 'mega', $megaAdsCount);
                $totalGenerated += $megaAdsCount;
            }
        }

        $this->info("Total de mega-anuncios generados: {$totalGenerated}");
        $this->info("Usuarios procesados: {$users->count()}");
        
        return Command::SUCCESS;
    }
}
