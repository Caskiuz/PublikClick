<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class UpdateUserRanks extends Command
{
    protected $signature = 'ranks:update';
    protected $description = 'Actualiza los rangos de todos los usuarios según sus referidos activos';

    public function handle()
    {
        $this->info('Actualizando rangos de usuarios...');

        $users = User::whereNotNull('current_package_id')->get();
        $updated = 0;

        foreach ($users as $user) {
            $result = $user->updateRank();
            
            if ($result['promoted']) {
                $updated++;
                $this->info("✓ {$user->name}: {$result['old_rank']->name} → {$result['new_rank']->name}");
            }
        }

        $this->info("✅ {$updated} usuarios actualizados");
        return 0;
    }
}
