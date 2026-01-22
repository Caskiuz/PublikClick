<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\MegaAd;
use App\Models\User;

class ResetMegaAds extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'megaads:reset {--force : Force reset without confirmation}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset monthly mega ads counters for all users';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if (!$this->option('force')) {
            if (!$this->confirm('¿Estás seguro de que quieres resetear todos los mega-anuncios mensuales?')) {
                $this->info('Operación cancelada.');
                return 0;
            }
        }

        $this->info('Reseteando mega-anuncios mensuales...');
        
        try {
            // Resetear contadores mensuales
            MegaAd::resetMonthlyCounters();
            
            $this->info('✅ Mega-anuncios reseteados exitosamente para todos los usuarios.');
            
            // Mostrar estadísticas
            $totalUsers = User::whereNotNull('current_rank_id')->count();
            $totalMegaAds = MegaAd::where('month', now()->month)
                                  ->where('year', now()->year)
                                  ->sum('total_available');
            
            $this->table(
                ['Métrica', 'Valor'],
                [
                    ['Usuarios con rango', $totalUsers],
                    ['Total mega-anuncios disponibles este mes', number_format($totalMegaAds)],
                    ['Valor total en mega-anuncios', '$' . number_format($totalMegaAds * 2000)]
                ]
            );
            
            return 0;
            
        } catch (\Exception $e) {
            $this->error('Error al resetear mega-anuncios: ' . $e->getMessage());
            return 1;
        }
    }
}