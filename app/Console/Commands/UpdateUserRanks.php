<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Rank;

class UpdateUserRanks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:update-ranks {--user= : Update specific user ID} {--dry-run : Show what would be updated without making changes}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update user ranks based on active referrals count';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Actualizando rangos de usuarios...');
        
        $query = User::with(['currentRank', 'activeReferrals']);
        
        // Si se especifica un usuario específico
        if ($this->option('user')) {
            $query->where('id', $this->option('user'));
        }
        
        $users = $query->get();
        $updated = 0;
        $promotions = [];
        
        foreach ($users as $user) {
            $activeReferralsCount = $user->activeReferrals()->count();
            $currentRank = $user->currentRank;
            $newRank = Rank::getRankByReferrals($activeReferralsCount);
            
            if (!$newRank) {
                $this->warn("No se encontró rango para usuario {$user->id} con {$activeReferralsCount} referidos");
                continue;
            }
            
            if (!$currentRank || $currentRank->id !== $newRank->id) {
                if (!$this->option('dry-run')) {
                    $result = $user->updateRank();
                    if ($result['promoted']) {
                        $updated++;
                        $promotions[] = [
                            'user' => $user->name . " (ID: {$user->id})",
                            'referrals' => $activeReferralsCount,
                            'old_rank' => $result['old_rank']->name ?? 'Sin rango',
                            'new_rank' => $result['new_rank']->name
                        ];
                    }
                } else {
                    $this->line("DRY RUN: Usuario {$user->name} (ID: {$user->id}) sería promovido de " . 
                              ($currentRank->name ?? 'Sin rango') . " a {$newRank->name} ({$activeReferralsCount} referidos)");
                }
            }
        }
        
        if ($this->option('dry-run')) {
            $this->info('Modo DRY RUN - No se realizaron cambios reales');
            return 0;
        }
        
        if ($updated > 0) {
            $this->info("✅ {$updated} usuarios actualizados exitosamente");
            
            if (!empty($promotions)) {
                $this->info('📈 Promociones realizadas:');
                $this->table(
                    ['Usuario', 'Referidos', 'Rango Anterior', 'Nuevo Rango'],
                    $promotions
                );
            }
        } else {
            $this->info('No se encontraron usuarios que requieran actualización de rango');
        }
        
        // Mostrar estadísticas generales
        $this->showRankStatistics();
        
        return 0;
    }
    
    private function showRankStatistics()
    {
        $this->info('📊 Estadísticas de rangos:');
        
        $ranks = Rank::withCount('users')->orderBy('order')->get();
        $stats = [];
        
        foreach ($ranks as $rank) {
            $stats[] = [
                $rank->name,
                $rank->users_count,
                $rank->min_referrals . '-' . ($rank->max_referrals ?? '∞'),
                '$' . number_format($rank->referral_commission),
                $rank->mega_ads_monthly
            ];
        }
        
        $this->table(
            ['Rango', 'Usuarios', 'Referidos Req.', 'Comisión/Click', 'Mega Ads/Mes'],
            $stats
        );
    }
}